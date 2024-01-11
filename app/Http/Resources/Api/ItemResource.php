<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => (float)$this->price,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'description' => $this->when($request->routeIs('supplier.items.show'), $this->description),
            'created_at' => $this->created_at->format('Y-m-d'), //Can be replaced with global MUTATOR
        ];
    }
}
