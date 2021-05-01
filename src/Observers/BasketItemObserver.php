<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Observers;

use Ctrlc\Basket\Models\BasketItem;
use Illuminate\Support\Facades\Log;

class BasketItemObserver
{
    public function created(BasketItem $basketItem)
    {
        Log::info('basket created', [$basketItem]);
    }

    public function updated(BasketItem $basketItem)
    {
        Log::info('basket updated', [$basketItem]);
    }

    public function deleted(BasketItem $basketItem)
    {
        Log::info('basket deleted', [$basketItem]);
    }

    public function forceDeleted(BasketItem $basketItem)
    {
        Log::info('basket force deleted', [$basketItem]);
    }
}
