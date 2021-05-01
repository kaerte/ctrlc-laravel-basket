<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Http\Controllers\API;

use Ctrlc\Basket\Contracts\ProductVariantContract;
use Ctrlc\Basket\Facades\Basket;
use Ctrlc\Basket\Resources\BasketResource;
use Illuminate\Routing\Controller;

class BasketController extends Controller
{
    public function getBasket()
    {
        return response()->json(new BasketResource(Basket::getFacadeRoot()));
    }

    //todo validate adding
    public function add(ProductVariantContract $variant)
    {
        try {
            Basket::add($variant);

            return response()->json(new BasketResource(Basket::getFacadeRoot()));
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    //todo validate removing
    public function remove(ProductVariantContract $variant)
    {
        try {
            Basket::remove($variant);

            return response()->json(new BasketResource(Basket::getFacadeRoot()));
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
