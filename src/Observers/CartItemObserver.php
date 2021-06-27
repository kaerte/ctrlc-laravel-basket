<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Observers;

use Ctrlc\Cart\Models\CartItem;
use Illuminate\Support\Facades\Log;

class CartItemObserver
{
    public function created(CartItem $basketItem)
    {
        Log::info('cart item created', [$basketItem]);
    }

    public function updated(CartItem $basketItem)
    {
        Log::info('cart item updated', [$basketItem]);
    }

    public function deleted(CartItem $basketItem)
    {
        Log::info('cart item deleted', [$basketItem]);
    }

    public function forceDeleted(CartItem $basketItem)
    {
        Log::info('cart item force deleted', [$basketItem]);
    }
}
