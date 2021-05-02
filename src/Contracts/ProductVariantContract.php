<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Contracts;

use Illuminate\Database\Eloquent\Relations\belongsTo;

interface ProductVariantContract
{
    public function getPriceAttribute(): int;

    public function getAvailableQuantityAttribute(): int;

    public function product(): belongsTo;
}
