<?php declare(strict_types=1);

namespace Ctrlc\Basket\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

interface ProductContract
{
    public function defaultVariant(): HasOne;

    public function variants(): HasMany;
}
