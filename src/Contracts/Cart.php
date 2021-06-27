<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Contracts;

use Ctrlc\Basket\Resources\BasketResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

interface Cart
{
    public function items(): Collection|HasMany|null;

    public function total(): int;

    public function add(ProductVariantContract $variant, ?int $quantity = 1, ?array $meta = []): Cart;

    public function remove(ProductVariantContract $variant, ?int $quantity = 1, ?array $meta = []);

    public function get(): Cart;

    public function create(): Cart;

    public function toJson(): BasketResource;
}
