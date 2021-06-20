<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphTo;

interface ProductVariantContract
{
    public function getPriceAttribute(): int;

    public function getAvailableQuantityAttribute(): int|null;

    public function productable(): MorphTo;
}
