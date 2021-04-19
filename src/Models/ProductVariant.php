<?php declare(strict_types=1);

namespace Ctrlc\Basket\Models;

use Ctrlc\Basket\Contracts\BasketItemVariantContract;
use Ctrlc\Basket\Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class ProductVariant extends Model implements BasketItemVariantContract
{
    use HasFactory;

    protected $casts = [
        'price' => 'int',
    ];

    public function item(): belongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function scropeDefault($query): Builder
    {
        return $query->where('default', 1);
    }

    public function scopeOfVariant($query, int $variantId): Builder
    {
        return $query->where('id', $variantId);
    }

    public function getPriceAttribute(): int
    {
        return (int) $this->attributes['price'];
    }

    protected static function newFactory(): ProductVariantFactory
    {
        return new ProductVariantFactory();
    }
}
