<?php declare(strict_types=1);

namespace Ctrlc\Basket\Models;

use Ctrlc\Basket\Contracts\BasketItemContract;
use Ctrlc\Basket\Database\Factories\BasketItemFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BasketItem extends Model implements BasketItemContract
{
    protected $casts = [
        'product_id' => 'int',
        'variant_id' => 'int',
        'quantity' => 'int',
    ];

    protected $fillable = [
        'quantity',
    ];

    public function product(): MorphTo
    {
        return $this->morphTo();
    }

    public function variant(): MorphTo
    {
        return $this->morphTo();
    }

    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class);
    }

    public function getPriceAttribute(): float|int
    {
        if ($this->variant_id) {
            return $this->product()->ofVariant($this->variant_id)->price;
        } else {
            //default variant
            return $this->product()->variant->price;
        }
    }

    protected static function newFactory()
    {
        return new BasketItemFactory();
    }
}
