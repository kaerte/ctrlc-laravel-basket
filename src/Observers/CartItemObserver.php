<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Observers;

use Ctrlc\Cart\Models\CartItem;
use Illuminate\Support\Facades\Log;

class CartItemObserver
{
    public function created(CartItem $cartItem)
    {
        Log::info('cart item created', [$cartItem]);
    }

    public function updated(CartItem $cartItem)
    {
        Log::info('cart item updated', [$cartItem]);
    }

    public function deleted(CartItem $cartItem)
    {
        Log::info('cart item deleted', [$cartItem]);
    }

    public function forceDeleted(CartItem $cartItem)
    {
        Log::info('cart item force deleted', [$cartItem]);
    }
}
