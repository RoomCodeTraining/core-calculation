<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Entity\EntityResource;
use App\Http\Resources\TransactionType\TransactionTypeResource;
use App\Http\Resources\Status\StatusResource;
use App\Http\Resources\User\UserResource;

class TransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->hashId,
            'reference' => $this->reference,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'cancellation_reason' => $this->cancellation_reason,
            'entity' => new EntityResource($this->whenLoaded('entity')),
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
