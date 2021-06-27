<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Facades;

use Ctrlc\Basket\Contracts\Cart as CartContract;
use Illuminate\Support\Facades\Facade;

class Cart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CartContract::class;
    }
}
