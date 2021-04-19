<?php declare(strict_types=1);

namespace Ctrlc\Basket\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request = null)
    {
        return [
            'id' => $this->id,
            'name' => $this->variant->name,
        ];
    }
}
