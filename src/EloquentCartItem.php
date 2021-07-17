<?php

declare(strict_types=1);

namespace Ctrlc\Cart;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Plank\Metable\Metable;

class EloquentCartItem extends Model
{
    use Metable;

    protected $table = 'cart_items';

    protected $casts = [
        'quantity' => 'int',
    ];

    protected $fillable = [
        'quantity',
    ];

    protected $with = ['item', 'meta'];

    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(EloquentCart::class);
    }

    public function getPriceAttribute(): float | int
    {
        return $this->item->price;
    }
}
