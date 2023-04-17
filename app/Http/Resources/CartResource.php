<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
            return [
            'id' => $this->id,
            'name' => $this->name,
            // 'image' => url(Storage::url($this->image)),
            'price' => $this->price,
            'quantity' => $this->quantity,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')), // Include the user relationship
            'product_id' => $this->product_id,
            'total_price' => $this->total_price,
        ];
    }
}
