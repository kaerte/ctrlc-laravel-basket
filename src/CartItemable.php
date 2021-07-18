<?php

declare(strict_types=1);

namespace Ctrlc\Cart;

interface CartItemable
{
    public function price(): float | int;

    public function availableQuantity(): int | null;
}
