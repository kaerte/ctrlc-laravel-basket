<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Plank\Metable\Metable;

class CartItem extends Model
{
    use Metable;

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
        return $this->belongsTo(Cart::class);
    }

    public function getPriceAttribute(): float | int
    {
        return $this->item->price;
    }
}