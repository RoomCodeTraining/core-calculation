<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Entity;
use App\Models\Status;
use GuzzleHttp\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\Vehicle;
use App\Enums\StatusEnum;
use App\Models\AppSetting;
use App\Models\Assignment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ExpertiseType;
use App\Models\AssignmentType;
use App\Enums\AssignmentTypeEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Jobs\GenerateInvoicePdfJob;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;
use App\Http\Resources\Invoice\InvoiceResource;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Gestion des factures
 *
 * APIs pour la gestion des factures
 */
class InvoiceController extends Controller
{
    use ApiResponse;
    
    public function __construct()
    {

    }

    /**
     * Lister toutes les factures
     *
     * @authenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $start_date = request()->filled('start_date') ? Carbon::parse(request()->start_date)->startOfDay() : null;
        $end_date = request()->filled('end_date') ? Carbon::parse(request()->end_date)->endOfDay() : null;
        $invoices = Invoice::with('transaction:id,reference,amount_excluding_tax,amount_tax,amount', 'status:id,code,label', 'createdBy:id,name,email,created_at', 'updatedBy:id,name,email,created_at', 'deletedBy:id,name,email,created_at', 'cancelledBy:id,name,email,created_at')
                    ->join('transactions', 'invoices.transaction_id', '=', 'transactions.id')
                    ->select('invoices.*', 'transactions.entity_id')
                    ->accessibleBy(auth()->user());
        
        if($start_date && $end_date){
            $invoices = $invoices->whereBetween('invoices.date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $invoices = $invoices->where('invoices.date', '>=', $start_date);
        } elseif ($end_date) {
            $invoices = $invoices->where('invoices.date', '<=', $end_date);
        }

        $entity_id = null;
        if(request()->filled('entity_id')){
            $entity_id = Entity::keyFromHashId(request()->entity_id);
            $invoices = $invoices->where('transactions.entity_id', $entity_id);
        }

        $status_id = null;
        if(request()->filled('status_code')){
            $status_id = Status::where('code', request()->status_code)->first()->id;
            $invoices = $invoices->where('status_id', $status_id);
        }

        $total_amount = $invoices->sum('transactions.quantity') * AppSetting::where('code', 'credit_cost')->first()->value;

        $invoices = $invoices->latest('invoices.created_at')->useFilters()->dynamicPaginate();

        if($start_date || $end_date || $status_id || $entity_id){
            $export_url = $this->export($start_date, $end_date, $status_id, $entity_id);
        }

        return InvoiceResource::collection($invoices)->additional([
            'export_url' => $export_url ?? null,
            'total_amount' => $total_amount,
        ]);
    }

    /**
     * Exporter les factures par période
     * 
     *
     * @authenticated
     */
    public function export($start_date, $end_date, $status_id, $entity_id) : string
    {        
        $start_date = $start_date ? Carbon::parse($start_date)->startOfDay() : null;
        $end_date = $end_date ? Carbon::parse($end_date)->endOfDay() : null;

        $invoices = \App\Models\Invoice::with('transaction:id,reference,amount_excluding_tax,amount_tax,amount', 'status:id,code,label')->accessibleBy(auth()->user());

        if ($start_date && $end_date) {
            $invoices = $invoices->whereBetween('invoices.date', [$start_date, $end_date]);
        } elseif ($start_date) {
            $invoices = $invoices->where('invoices.date', '>=', $start_date);
        } elseif ($end_date) {
            $invoices = $invoices->where('invoices.date', '<=', $end_date);
        }

        if($entity_id){
            $invoices = $invoices->where('transactions.entity_id', $entity_id);
        }

        if($status_id){
            $invoices = $invoices->where('status_id', $status_id);
        }

        $invoices = $invoices->latest('invoices.created_at')
            ->useFilters()
            ->get();

        $exportData = [];
        // En-tête
        $exportData[] = [
            'Référence',
            'Transaction',
            'Montant',
            'Statut',
            'Date de création'
        ];

        foreach ($invoices as $invoice) {
            $exportData[] = [
                $invoice->reference,
                $invoice->transaction ? $invoice->transaction->reference : '',
                $invoice->transaction->quantity * AppSetting::where('code', 'credit_cost')->first()->value,
                $invoice->status ? $invoice->status->label : '',
                $invoice->created_at ?  $invoice->created_at->format('d/m/Y H:i:s') : '',
            ];
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Factures');

        foreach ($exportData as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $value);
            }
        }

        // Utilisation de Maatwebsite\Excel\Facades\Excel pour exporter
        $filename = 'export_factures.xlsx';
        $filepath = storage_path('app/public/exports/' . $filename);

        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0777, true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filepath);

        $url = asset('storage/exports/' . auth()->user()->code . '/' . $filename);

        return $url;
    }

    /**
     * Statistiques des factures
     *
     * @authenticated
     */
    public function statistics(): JsonResponse
    {
        $start_date = request()->filled('start_date') ? Carbon::parse(request()->start_date)->startOfDay() : null;
        $end_date = request()->filled('end_date') ? Carbon::parse(request()->end_date)->endOfDay() : null;

        $invoices_by_year_and_month_count = Invoice::join('transactions', 'invoices.transaction_id', '=', 'transactions.id')
            ->selectRaw('YEAR(invoices.created_at) as year, MONTH(invoices.created_at) as month, COUNT(*) as count')
            ->where(['transactions.entity_id' => auth()->user()->entity_id]);

        if($start_date && $end_date){
            $invoices_by_year_and_month_count = $invoices_by_year_and_month_count->whereBetween('invoices.created_at', [$start_date, $end_date]);
        } elseif ($start_date) {
            $invoices_by_year_and_month_count = $invoices_by_year_and_month_count->where('invoices.created_at', '>=', $start_date);
        } elseif ($end_date) {
            $invoices_by_year_and_month_count = $invoices_by_year_and_month_count->where('invoices.created_at', '<=', $end_date);
        }

        $entity_id = null;
        if(request()->filled('entity_id')){
            $entity_id = Entity::keyFromHashId(request()->entity_id);
            $invoices_by_year_and_month_count = $invoices_by_year_and_month_count->where('transactions.entity_id', $entity_id);
        }

        $status_id = null;
        if(request()->filled('status_code')){
            $status_id = Status::where('code', request()->status_code)->first()->id;
            $invoices_by_year_and_month_count = $invoices_by_year_and_month_count->where('status_id', $status_id);
        }

        $invoices_by_year_and_month_count = $invoices_by_year_and_month_count
            ->groupBy(DB::raw('YEAR(invoices.created_at)'), DB::raw('MONTH(invoices.created_at)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->useFilters()
            ->get();

        $invoices_by_year_and_month_amount = Invoice::join('transactions', 'invoices.transaction_id', '=', 'transactions.id')
            ->selectRaw('YEAR(invoices.created_at) as year, MONTH(invoices.created_at) as month, SUM(transactions.quantity) * '.AppSetting::where('code', 'credit_cost')->first()->value.' as amount')
            ->where(['transactions.entity_id' => auth()->user()->entity_id]);

        if($start_date && $end_date){
            $invoices_by_year_and_month_amount = $invoices_by_year_and_month_amount->whereBetween('invoices.created_at', [$start_date, $end_date]);
        } elseif ($start_date) {
            $invoices_by_year_and_month_amount = $invoices_by_year_and_month_amount->where('invoices.created_at', '>=', $start_date);
        } elseif ($end_date) {
            $invoices_by_year_and_month_amount = $invoices_by_year_and_month_amount->where('invoices.created_at', '<=', $end_date);
        }

        $entity_id = null;
        if(request()->filled('entity_id')){
            $entity_id = Entity::keyFromHashId(request()->entity_id);
            $invoices_by_year_and_month_amount = $invoices_by_year_and_month_amount->where('transactions.entity_id', $entity_id);
        }

        $status_id = null;
        if(request()->filled('status_code')){
            $status_id = Status::where('code', request()->status_code)->first()->id;
            $invoices_by_year_and_month_amount = $invoices_by_year_and_month_amount->where('status_id', $status_id);
        }

        $invoices_by_year_and_month_amount = $invoices_by_year_and_month_amount
            ->groupBy(DB::raw('YEAR(invoices.created_at)'), DB::raw('MONTH(invoices.created_at)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->useFilters()
            ->get();

        // Export des statistiques des factures

        $export_invoices_by_year_and_month_count_data = [];
        // En-tête
        $export_invoices_by_year_and_month_count_data[] = [
            'Année',
            'Mois',
            'Nombre de factures'
        ];

        foreach ($invoices_by_year_and_month_count as $invoice) {
            $export_invoices_by_year_and_month_count_data[] = [
                $invoice->year,
                $invoice->month,
                $invoice->count,
            ];
        }

        $export_invoices_by_year_and_month_amount_data = [];
        // En-tête
        $export_invoices_by_year_and_month_amount_data[] = [
            'Année',
            'Mois',
            'Montant des factures'
        ];

        foreach ($invoices_by_year_and_month_amount as $invoice) {
            $export_invoices_by_year_and_month_amount_data[] = [
                $invoice->year,
                $invoice->month,
                $invoice->amount,
            ];
        }

        // Création d'un classeur avec deux feuilles
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Première feuille : Nombre de dossiers par année et mois
        $sheetCount = $spreadsheet->getActiveSheet();
        $sheetCount->setTitle('Nombre de factures');

        foreach ($export_invoices_by_year_and_month_count_data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheetCount->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $value);
            }
        }

        // Deuxième feuille : Montant des dossiers par année et mois
        $sheetAmount = $spreadsheet->createSheet();
        $sheetAmount->setTitle('Montant des factures');

        foreach ($export_invoices_by_year_and_month_amount_data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheetAmount->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $value);
            }
        }

        // Sauvegarde du fichier
        $filename = 'export_factures_stats.xlsx';
        $filepath = storage_path('app/public/exports/' . $filename);

        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0777, true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filepath);

        $url = asset('storage/exports/' . auth()->user()->code . '/' . $filename);

        return $this->responseSuccess('Statistiques des factures', [
            'invoices_by_year_and_month_count' => $invoices_by_year_and_month_count,
            'invoices_by_year_and_month_amount' => $invoices_by_year_and_month_amount,
            'export_url' => $url,
        ]);
    }

    /**
     * Créer une facture
     * 
     *
     * @authenticated
     */
    public function store(CreateInvoiceRequest $request): JsonResponse
    {
        $transaction = Transaction::accessibleBy(auth()->user())->findOrFail($request->transaction_id);

        // if(Invoice::where(['transaction_id' => $transaction->id, 'type' => $request->type, 'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id])->exists()){
        //     return $this->responseUnprocessable("La facture est déjà générée pour cette transaction.");
        // }

        // $receipt_amount = Receipt::where('transaction_id', $transaction->id)->sum('amount');
        // if(!$receipt_amount || $receipt_amount == 0){
        //     return $this->responseUnprocessable("Cette transaction n'a aucune quittance.");
        // }

        $now = Carbon::now();
        $annee = date("Y");
        $mois_jour_heure = date("mdH");
        $time = date("is");
        $today = $annee.'_'.$mois_jour_heure.'_'.$time;
        $reference = 'F'.$today;

        $invoice = Invoice::create([
            'reference' => $reference,
            'date' => $request->date,
            'object' => $request->object,
            'transaction_id' => $transaction->id,
            'type' => $request->type,
            'invoice_reference' => $request->type == 'credit_bill' ? $request->invoice_reference : null,
            'payment_method' => $request->payment_method,
            'template' => $request->template,
            'is_fne' => $request->is_fne,
            'foreign_currency' => $request->foreign_currency,
            'foreign_currency_rate' => $request->foreign_currency_rate,
            'discount' => $request->discount,
            'status_id' => Status::where('code', StatusEnum::ACTIVE)->first()->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        if($request->address || $request->taxpayer_account_number){
            if($transaction->entity->entity_type_code == EntityType::where('code', EntityTypeEnum::INSURER)->first()->hashId){ // Si le client existe
                $entity = Entity::findOrFail($transaction->entity_id);
                $entity->update([
                    'address' => $request->address,
                    'taxpayer_account_number' => $request->taxpayer_account_number,
                ]);
            }  else {
                $client = Client::findOrFail($transaction->entity_id);
                $client->update([
                    'address' => $request->address,
                    'taxpayer_account_number' => $request->taxpayer_account_number,
                ]);
            }
        }

        if($request->type == 'credit_bill' && $request->invoice_reference){
            Invoice::where('transaction_id', $transaction->id)->where('reference', $request->invoice_reference)->update([
                'status_id' => Status::where('code', StatusEnum::CANCELLED)->first()->id,
            ]);
        }

        dispatch(new GenerateInvoicePdfJob($invoice));

        return $this->responseCreated('Invoice created successfully', new InvoiceResource($invoice));
    }

    /**
     * Afficher une facture
     *
     * @authenticated
     */
    public function show($id): JsonResponse
    {
        $invoice = Invoice::join('transactions', 'invoices.transaction_id', '=', 'transactions.id')
            ->accessibleBy(auth()->user())
            ->where('invoices.id', Invoice::keyFromHashId($id))
            ->first();

        return $this->responseSuccess(null, new InvoiceResource($invoice->load('transaction:id,reference,amount_excluding_tax,amount_tax,amount', 'status:id,code,label', 'createdBy:id,name,email,created_at', 'updatedBy:id,name,email,created_at', 'deletedBy:id,name,email,created_at', 'cancelledBy:id,name,email,created_at')));
    }

    /**
     * Mettre à jour une facture
     *
     * @authenticated
     */
    public function update(UpdateInvoiceRequest $request, $id): JsonResponse
    {
        $invoice = Invoice::join('transactions', 'invoices.transaction_id', '=', 'transactions.id')
            ->accessibleBy(auth()->user())
            ->where('invoices.id', Invoice::keyFromHashId($id))
            ->firstOrFail();

        $invoice->update([
            'date' => $request->date,
            'object' => $request->object,
            'address' => $request->address,
            'taxpayer_account_number' => $request->taxpayer_account_number,
            'updated_by' => auth()->user()->id,
        ]);

        dispatch(new GenerateInvoicePdfJob($invoice));

        return $this->responseSuccess('Invoice updated Successfully', new InvoiceResource($invoice));
    }

    /**
     * Générer une facture
     *
     * @authenticated
     */
    public function generate($id): JsonResponse
    {
        $invoice = Invoice::join('transactions', 'invoices.transaction_id', '=', 'transactions.id')
            ->accessibleBy(auth()->user())
            ->where('invoices.id', Invoice::keyFromHashId($id))
            ->firstOrFail();
        
        dispatch(new GenerateInvoicePdfJob($invoice));

        return $this->responseSuccess('Facture générée avec succès', new InvoiceResource($invoice));
    }

    /**
     * Annuler une facture
     *
     * @authenticated
     */
    public function cancel($id): JsonResponse
    {
        $invoice = Invoice::join('transactions', 'invoices.transaction_id', '=', 'transactions.id')
            ->accessibleBy(auth()->user())
            ->where('invoices.id', Invoice::keyFromHashId($id))
            ->firstOrFail();

        $invoice->update([
            'status_id' => Status::where('code', StatusEnum::CANCELLED)->first()->id,
            'cancelled_by' => auth()->user()->id,
            'cancelled_at' => Carbon::now(),
            'updated_by' => auth()->user()->id,
        ]);

        $transaction = Transaction::findOrFail($invoice->transaction_id);

        $transaction->update([
            'status_id' => Status::where('code', StatusEnum::CANCELLED)->first()->id,
            'cancelled_by' => auth()->user()->id,
            'cancelled_at' => Carbon::now(),
            'updated_by' => auth()->user()->id,
        ]);

        return $this->responseSuccess('Facture annulée avec succès', new InvoiceResource($invoice));
    }

    /**
     * Supprimer une facture
     *
     * @authenticated
     */
    public function destroy($id): JsonResponse
    {
        $invoice = Invoice::findOrFail(Invoice::keyFromHashId($id));

        // $invoice->delete();

        return $this->responseDeleted();
    }

   
}
