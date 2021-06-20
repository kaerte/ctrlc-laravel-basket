<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Models;

use Ctrlc\Basket\Contracts\ProductVariantContract;
use Ctrlc\Basket\Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Plank\Metable\Metable;

class ProductVariant extends Model implements ProductVariantContract
{
    use HasFactory;
    use Metable;

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'default',
    ];

    protected $casts = [
        'price' => 'int',
        'quantity' => 'int',
        'default' => 'int',
    ];

    protected $with = ['productable'];

    public function productable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getPriceAttribute(): int
    {
        return (int) $this->attributes['price'];
    }

    public function getAvailableQuantityAttribute(): int
    {
        return $this->quantity;
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
