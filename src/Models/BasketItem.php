<?php

declare(strict_types=1);

namespace Ctrlc\Basket\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Plank\Metable\Metable;

class BasketItem extends Model
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

    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class);
    }

    public function getPriceAttribute(): float | int
    {
        return $this->item->price;
    }
}
