<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasketItemResource extends JsonResource
{
    public function toArray($request = null)
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => ($this->item->price) * $this->quantity,
            'name' => $this->item->name,
            'metaData' => $this->metaData,
        ];
    }
}
