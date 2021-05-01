<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Facades;

use Illuminate\Support\Facades\Facade;

class Basket extends Facade
{
    protected static function getFacadeAccessor()
    {
        /**
         * @mixin \Ctrlc\Basket\Models\Basket
         */
        return 'basket';
    }
}
