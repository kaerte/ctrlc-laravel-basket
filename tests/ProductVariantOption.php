<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariantOption extends Model
{
    use HasFactory;

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariant::class);
    }

    protected static function newFactory(): ProductVariantOptionFactory
    {
        return new ProductVariantOptionFactory();
    }
}
