<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
//        return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'product_type' => ProductTypeResource::make($this->whenLoaded('type')),
            'created_by' => UserResource::make($this->whenLoaded('creator')),
            'created_at' => $this->created_at->format('m/d/Y g:i A') ,
        ];
    }
}
