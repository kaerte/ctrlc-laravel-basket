<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BasketItem extends Model
{
    protected $casts = [
        'quantity' => 'int',
    ];

    protected $fillable = [
        'quantity',
    ];

    protected $with = ['item'];

    public function item(): MorphTo
    {
        return $this->morphTo();
    }

    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class);
    }

    public function getPriceAttribute(): float | int
    {
        return $this->item->price;
    }
}
