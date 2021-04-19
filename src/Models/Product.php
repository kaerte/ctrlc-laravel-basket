<?php declare(strict_types=1);

namespace Ctrlc\Basket\Models;

use Ctrlc\Basket\Contracts\BasketItemContract;
use Ctrlc\Basket\Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model implements BasketItemContract
{
    use HasFactory;

    protected $with = ['variant'];

    public function variant(): HasOne
    {
        return $this->hasOne(ProductVariant::class)->where('default', 1);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function product(): Model
    {
        return $this;
    }

    protected static function newFactory(): ProductFactory
    {
        return new ProductFactory();
    }
}
