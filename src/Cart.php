<?php

declare(strict_types=1);

namespace Ctrlc\Cart;

use JsonSerializable;

interface Cart
{
    public function cartable(): mixed;

    /**
    * @return CartItem[]
    */
    public function items(): mixed;

    public function total(): int;

    public function discountedAmount(): int;

    public function add(CartItemable $cartItem, ?int $quantity = 1, ?array $meta = []): self;

    public function remove(CartItemable $cartItem, ?array $meta = []);

    public function updateQuantity(CartItemable $cartItem, ?int $quantity = 1, ?array $meta = []): self;
    
    public function get(): self;

    public function create(): self;

    public function removeCart(): null|bool;

    public function merge(Cart ...$carts): self;
    
    public function discountCode(): mixed;

    public function addDiscountCode(mixed $discountCode): self;
    
    public function removeDiscountCode(): self;

    public function toJson(): JsonSerializable;

    public function isEmpty(): bool;
}
