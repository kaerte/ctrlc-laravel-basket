<?php declare(strict_types=1);

namespace Ctrlc\Basket\Contracts;

use Illuminate\Database\Eloquent\Relations\belongsTo;

interface BasketItemVariantContract
{
    public function getPriceAttribute(): int;

    public function item(): belongsTo;
}
