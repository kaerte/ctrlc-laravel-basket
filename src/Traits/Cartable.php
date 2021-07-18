<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Traits;

use Ctrlc\Cart\EloquentCart;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Cartable
{
    public function cart(): MorphOne
    {
        return $this->morphOne(EloquentCart::class, 'cartable');
    }
}
