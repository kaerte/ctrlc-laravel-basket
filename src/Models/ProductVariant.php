<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Models;

use Ctrlc\Cart\Contracts\ProductVariantContract;
use Ctrlc\Cart\Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function getAvailableQuantityAttribute(): int|null
    {
        return $this->quantity;
    }

    public function scropeDefault($query): Builder
    {
        return $query->where('default', 1);
    }

    protected static function newFactory(): ProductVariantFactory
    {
        return new ProductVariantFactory();
    }
}
