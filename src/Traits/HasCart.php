<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Traits;

use Ctrlc\Basket\Models\Cart;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasCart
{
    public function basket(): MorphOne
    {
        return $this->morphOne(Cart::class, 'basketable');
    }
}
