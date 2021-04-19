<?php declare(strict_types=1);

namespace Ctrlc\Basket\Http\Controllers\API;

use Ctrlc\Basket\Models\Product;
use Ctrlc\Basket\Resources\ProductResource;
use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}
