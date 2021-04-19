<?php declare(strict_types=1);

namespace Ctrlc\Basket\Http\Controllers\API;

use Ctrlc\Basket\Contracts\BasketItemVariantContract;
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
    public function add(BasketItemVariantContract $productVariant)
    {
        try {
            Basket::add($productVariant);

            return response()->json(new BasketResource(Basket::getFacadeRoot()));
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    //todo validate removing
    public function remove(BasketItemVariantContract $productVariant)
    {
        try {
            Basket::remove($productVariant);

            return response()->json(new BasketResource(Basket::getFacadeRoot()));
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
}
