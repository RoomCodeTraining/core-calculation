<?php

namespace App\Jobs;

use PDF;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shock;
use App\Models\Entity;
use App\Models\QrCode;
use App\Models\Status;
use App\Models\Payment;
use App\Enums\StatusEnum;
use App\Enums\ProfileEnum;
use App\Models\Assignment;
use App\Models\WorkforceType;
use App\Models\Calculation;
use Illuminate\Bus\Queueable;
use App\Models\ArticleRequest;
use NumberToWords\NumberToWords;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateEvaluationReportPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public $maxExceptions = 1;

    public $backoff = [20, 40, 60];

    public $timeout = 300;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     */
    public function __construct(public Calculation $_calculation)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $calculation = Calculation::with('entity', 'vehicleCharacteristic')
                        ->where('calculations.id', $this->_calculation->id)
                        ->first();

        $evaluation = json_decode($calculation->evaluation);

        $path_qr_code = base_path('public/images/qr_code.png');
        $type_qr_code = pathinfo($path_qr_code, PATHINFO_EXTENSION);
        $data_qr_code = file_get_contents($path_qr_code);
        $qr_code = 'data:image/'.$type_qr_code.';base64,'.base64_encode($data_qr_code);

        $logoEntity = Entity::select('logo')->find($calculation->entity->id);

        $logo = $logoEntity && $logoEntity->logo
        ? image_to_base64(public_path("storage/logos/{$logoEntity->logo}"))
        : image_to_base64(base_path('public/images/logo.png'));

        $path_check_icon = base_path('public/images/check-icon.png');
        $type_check_icon = pathinfo($path_check_icon, PATHINFO_EXTENSION);
        $data_check_icon = file_get_contents($path_check_icon);
        $check_icon = 'data:image/'.$type_check_icon.';base64,'.base64_encode($data_check_icon);

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('fr');

        $pdf = PDF::loadView('evaluation_report/index',compact('calculation','evaluation','logo','check_icon','qr_code','numberTransformer'));
        $pdf->set_option('isHtml5ParserEnabled', false);
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->setOptions(['defaultFont' => 'sans-serif']);
        $pdf->save(public_path("storage/evaluation_report/".$calculation->reference.".pdf"));
        $pdf->setBasePath($_SERVER['DOCUMENT_ROOT']);
    }
}
