<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Traits;

use Ctrlc\Cart\Models\Cart;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasCart
{
    public function cart(): MorphOne
    {
        return $this->morphOne(Cart::class, 'cartable');
    }
}
