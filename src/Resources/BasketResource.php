<?php declare(strict_types=1);

namespace Ctrlc\Basket\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasketResource extends JsonResource
{
    public function toArray($request = null)
    {
        return [
            'id' => $this->id,
            'total' => $this->total,
            'items' => BasketItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
