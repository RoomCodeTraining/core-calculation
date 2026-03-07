<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Entity\EntityResource;
use App\Http\Resources\Status\StatusResource;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->hashId,
            'reference' => $this->reference,
            'quantity' => $this->quantity,
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'entity' => new EntityResource($this->whenLoaded('entity')),
            'validated_by' => new UserResource($this->whenLoaded('validatedBy')),
            'validated_at' => dateTimeFormat($this->validated_at),
            'cancelled_by' => new UserResource($this->whenLoaded('cancelledBy')),
            'cancelled_at' => dateTimeFormat($this->cancelled_at),
            'cancellation_reason' => $this->cancellation_reason,
            'rejected_by' => new UserResource($this->whenLoaded('rejectedBy')),
            'rejected_at' => dateTimeFormat($this->rejected_at),
            'rejection_reason' => $this->rejection_reason,
            'status' => new StatusResource($this->whenLoaded('status')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            'deleted_by' => new UserResource($this->whenLoaded('deletedBy')),
            'deleted_at' => dateTimeFormat($this->deleted_at),
            'created_at' => dateTimeFormat($this->created_at),
            'updated_at' => dateTimeFormat($this->updated_at),
        ];
    }
}
