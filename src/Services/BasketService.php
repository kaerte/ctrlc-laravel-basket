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
        $this->basket = $this->getBasket();
    }

    protected function getBasket()
    {
        $this->createBasket();

        return $this->basket;
    }

    protected function createBasket()
    {
        $this->basket = new Basket();
        $this->basket->save();
        $this->basket = $this->basket->fresh();
    }
}
