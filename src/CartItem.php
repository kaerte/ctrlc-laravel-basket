<?php

declare(strict_types=1);

namespace Ctrlc\Cart;

interface CartItem
{
    public function item(): mixed;

    public function cart(): mixed;

    public function price(): float | int;
}
