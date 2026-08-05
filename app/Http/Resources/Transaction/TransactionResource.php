<?php

namespace App\Http\Resources\Transaction;

use App\Enums\StatusEnum;
use App\Http\Resources\Calculation\CalculationResource;
use App\Http\Resources\Entity\EntityResource;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Receipt\ReceiptResource;
use App\Http\Resources\Status\StatusResource;
use App\Http\Resources\TransactionType\TransactionTypeResource;
use App\Http\Resources\User\UserResource;
use App\Models\Status;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        $status_performed_id = Status::where('code', StatusEnum::PERFORMED)->first()->id;

        return [
            'id' => $this->hashId,
            'reference' => $this->reference,
            'quantity' => $this->quantity,
            'amount' => $this->amount ?? (
                $this->quantity !== null
                    ? (float) $this->quantity * (float) config('services.settings.transaction_cost', 100)
                    : null
            ),
            'description' => $this->description,
            'cancellation_reason' => $this->cancellation_reason,
            'entity' => new EntityResource($this->whenLoaded('entity')),
            'order' => new OrderResource($this->whenLoaded('order')),
            'calculation' => new CalculationResource($this->whenLoaded('calculation')),
            'receipts' => $this->status_id === $status_performed_id ? ReceiptResource::collection($this->whenLoaded('receipts')) : null,
            'transaction_type' => new TransactionTypeResource($this->whenLoaded('transactionType')),
            'status' => new StatusResource($this->whenLoaded('status')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            'deleted_by' => new UserResource($this->whenLoaded('deletedBy')),
            'cancelled_by' => new UserResource($this->whenLoaded('cancelledBy')),
            'cancelled_at' => dateTimeFormat($this->cancelled_at),
            'deleted_at' => dateTimeFormat($this->deleted_at),
            'created_at' => dateTimeFormat($this->created_at),
            'updated_at' => dateTimeFormat($this->updated_at),
        ];
    }
}
