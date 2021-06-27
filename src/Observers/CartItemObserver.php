<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Observers;

use Ctrlc\Basket\Models\CartItem;
use Illuminate\Support\Facades\Log;

class CartItemObserver
{
    public function created(CartItem $basketItem)
    {
        Log::info('basket created', [$basketItem]);
    }

    public function updated(CartItem $basketItem)
    {
        Log::info('basket updated', [$basketItem]);
    }

    public function deleted(CartItem $basketItem)
    {
        Log::info('basket deleted', [$basketItem]);
    }

    public function forceDeleted(CartItem $basketItem)
    {
        Log::info('basket force deleted', [$basketItem]);
    }
}
