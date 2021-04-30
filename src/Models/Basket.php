<?php declare(strict_types=1);

namespace Ctrlc\Basket\Models;

use Ctrlc\Basket\Contracts\ProductVariantContract;
use Ctrlc\Basket\Database\Factories\BasketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Basket extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['total'];

    public function instance(): Basket
    {
        return $this;
    }

    public function basketable(): MorphTo
    {
        return $this->morphTo();
    }

    public function items(): HasMany
    {
        return $this->hasMany(BasketItem::class);
    }

    public function getTotalAttribute(): float|int
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->price * $item->quantity;
        }

        return $total;
    }

    public function total(): float|int
    {
        return $this->getTotalAttribute();
    }

    public function add(ProductVariantContract $variant, ?int $quantity = 1): Basket
    {
        if ($variant->getAvailableQuantityAttribute() < $quantity) {
            throw new \InvalidArgumentException('Product of this quantity is not in stock');
        }

        \DB::transaction(function () use ($variant, $quantity) {
            $this->fresh('items.item');
            $variant->load('item');

            $basketItem = $this->getBasketItem($variant);

            if (!$basketItem) {
                $basketItem = new BasketItem([
                    'quantity' => $quantity,
                ]);
                $basketItem->basket()->associate($this);
                $basketItem->item()->associate($variant);
                $this->items()->save($basketItem);
                $this->save();
            } else {
                $basketItem->quantity += $quantity;
                $basketItem->save();
            }
        }, 5);
        $this->load('items');

        return $this;
    }

    public function remove(ProductVariantContract $variant, ?int $quantity = 1): Basket
    {
        if (!config('ctrlc.basket.allow_remove')) {
            throw new \InvalidArgumentException('Removing items from basket is disabled');
        }

        \DB::transaction(function () use ($variant, $quantity) {
            $basketItem = $this->getBasketItem($variant);
            if ($basketItem->quantity === $quantity) {
                $basketItem->delete();
            }
            if ($basketItem->quantity >= ($quantity + 1)) {
                $basketItem->quantity -= $quantity;
                $basketItem->save();
            }
        }, 5);

        $this->load('items');

        return $this;
    }

    private function getBasketItem(ProductVariantContract $variant)
    {
        return $this->items()
            ->where('item_id', $variant->getKey())
            ->where('item_type', $variant::class)
            ->limit(1)
            ->get()
            ->first();
    }

    protected static function newFactory(): BasketFactory
    {
        return BasketFactory::new();
    }
}
