<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Services;

use Ctrlc\Basket\Models\Basket;

class BasketService
{
    public Basket | null $basket = null;

    public array $items = [];

    public int $total = 0;

    public function __construct()
    {
        $this->getBasket();
    }

    //todo implement basket lock, this method should return new basket if basket was locked
    protected function getBasket()
    {
        $basket = $this->createBasket();

        return $basket;
    }

    protected function createBasket(): ?Basket
    {
        $this->basket = new Basket();
        $this->basket->save();
        return $this->basket->fresh();
    }
}
