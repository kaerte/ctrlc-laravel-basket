<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request = null)
    {
        return [
            'id' => $this->id,
            'total' => $this->total,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
