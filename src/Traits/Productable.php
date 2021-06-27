<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Traits;

use Ctrlc\Cart\Models\ProductVariant;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Productable
{
    public function defaultVariant(): MorphOne
    {
        return $this->morphOne(ProductVariant::class, 'productable')->where('default', 1);
    }

    public function variants(): MorphMany
    {
        return $this->morphMany(ProductVariant::class, 'productable');
    }
}
