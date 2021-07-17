<?php

declare(strict_types=1);

namespace Ctrlc\Cart;

interface CartItem
{
    public function item(): CartItemable;

    public function cart(): Cart;

    public function getPriceAttribute(): float | int;
}
