<?php

declare(strict_types=1);

namespace Ctrlc\Cart\Models;

use Ctrlc\Cart\Contracts\Cart as CartContract;
use Ctrlc\Cart\Contracts\ProductVariantContract;
use Ctrlc\Cart\Database\Factories\CartFactory;
use Ctrlc\Cart\Resources\CartResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Cart extends Model implements CartContract
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['total'];

    protected $with = ['cartable'];

    public function instance(): Cart
    {
        return $this;
    }

    public function cartable(): MorphTo
    {
        return $this->morphTo();
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalAttribute(): float | int
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->price * $item->quantity;
        }

        return $total;
    }

    public function total(): int
    {
        return $this->getTotalAttribute();
    }

    public function add(ProductVariantContract $variant, ?int $quantity = 1, ?array $meta = []): Cart
    {
        $this->fresh('items.item');
        $basketItem = $this->getCartItem($variant, $meta);
        $basketQuantity = (int) ($basketItem?->quantity ?? 0);

        if ($variant->getAvailableQuantityAttribute() && $variant->getAvailableQuantityAttribute() < ($quantity + $basketQuantity)) {
            throw new \InvalidArgumentException('Product of this quantity is not in stock');
        }

        \DB::transaction(function () use ($variant, $quantity, $basketItem) {
            if (!$basketItem) {
                $basketItem = new CartItem([
                    'quantity' => $quantity,
                ]);
                $basketItem->cart()->associate($this);
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

    public function remove(ProductVariantContract $variant, ?int $quantity = 1, ?array $meta = []): Cart
    {
        if (! config('ctrlc.cart.allow_remove')) {
            throw new \InvalidArgumentException('Removing items from basket is disabled');
        }

        \DB::transaction(function () use ($variant, $quantity, $meta) {
            $basketItem = $this->getCartItem($variant, $meta);
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

    private function getCartItem(ProductVariantContract $variant, array $meta)
    {
        return $this->items()
            ->where('item_id', $variant->getKey())
            ->where('item_type', $variant::class)
            ->when(!empty($meta), function (Builder $query) use ($meta) {
                foreach ($meta as $key => $value) {
                    $query->whereMeta($key, $value)->get();
                }
            })
            ->limit(1)
            ->get()
            ->first();
    }

    protected static function newFactory(): CartFactory
    {
        return CartFactory::new();
    }

    public function get(): Cart
    {
        $basket = new self();
        $basket->save();

        return $basket->fresh();
    }

    public function create(): Cart
    {
        $basket = new self();
        $basket->save();

        return $basket->fresh();
    }

    public function toJson($options = 0): CartResource
    {
        return new CartResource($this);
    }
}
