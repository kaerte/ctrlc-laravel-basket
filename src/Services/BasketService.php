<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Services;

use Ctrlc\Basket\Contracts\ProductVariantContract;
use Ctrlc\Basket\Models\Basket;

class BasketService
{
    public Basket | null $basket = null;

    public array $items = [];

    public int $total = 0;

    public function __construct()
    {
        $this->basket = $this->getBasket();
    }

    public function items()
    {
        return $this->basket?->items;
    }

    public function total(): int
    {
        return $this->basket->total;
    }

    public function add(ProductVariantContract $variant, ?int $quantity = 1, ?array $meta = [])
    {
        return $this->basket->add($variant, $quantity, $meta);
    }

    public function remove(ProductVariantContract $variant, ?int $quantity = 1, ?array $meta = [])
    {
        return $this->basket->remove($variant, $quantity, $meta);
    }

    public function getBasket()
    {
        if (empty($this->basket)) {
            $this->createBasket();
        }

        return $this->basket;
    }

    protected function createBasket()
    {
        $this->basket = new Basket();
        $this->basket->save();
        $this->basket = $this->basket->fresh();
    }
}
