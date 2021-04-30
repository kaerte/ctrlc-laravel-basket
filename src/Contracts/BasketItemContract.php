<?php declare(strict_types=1);

namespace Ctrlc\Basket\Contracts;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface BasketItemContract
{
    public function variant(): HasOne;
}
