<?php

declare(strict_types=1);

namespace Ctrlc\Cart;

use Ctrlc\Cart\Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Plank\Metable\Metable;

class ProductVariant extends Model implements CartItemable
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

    protected $with = ['meta', 'productable'];
    
    public function productable(): MorphTo
    {
        return $this->morphTo();
    }

    public function price(): int
    {
        return (int) $this->attributes['price'];
    }

    public function availableQuantity(): int|null
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
