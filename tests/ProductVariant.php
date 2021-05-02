<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests;

use Ctrlc\Basket\Contracts\ProductVariantContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class ProductVariant extends Model implements ProductVariantContract
{
    use HasFactory;

    protected $casts = [
        'price' => 'int',
        'quantity' => 'int',
        'default' => 'int',
    ];

    public function product(): belongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getPriceAttribute(): int
    {
        return (int) $this->attributes['price'];
    }

    public function getAvailableQuantityAttribute(): int
    {
        return (int) ($this->quantity || 0);
    }

    public function scropeDefault($query): Builder
    {
        return $query->where('default', 1);
    }

    public function scopeOfVariant($query, int $variantId): Builder
    {
        return $query->where('id', $variantId);
    }

    protected static function newFactory(): ProductVariantFactory
    {
        return new ProductVariantFactory();
    }
}
