<?php

declare(strict_types=1);

namespace Ctrlc\Cart;

interface CartItemable
{
    public function getPriceAttribute(): float | int;

    public function getAvailableQuantityAttribute(): int | null;
}
