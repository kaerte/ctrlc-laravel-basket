<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Traits;

use Ctrlc\Basket\Models\Basket;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasBasket
{
    public function basket(): MorphOne
    {
        return $this->morphOne(Basket::class, 'owner');
    }
}
