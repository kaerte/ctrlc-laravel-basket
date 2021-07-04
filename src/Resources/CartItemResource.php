<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request = null)
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => ($this->item->price) * $this->quantity,
            'name' => $this->item->name,
            'meta' => $this->getAllMeta(),
        ];
    }
}
