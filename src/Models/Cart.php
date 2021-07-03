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
        $cartItem = $this->getCartItem($variant, $meta);
        $cartQuantity = (int) ($cartItem?->quantity ?? 0);

        if ($variant->getAvailableQuantityAttribute() && $variant->getAvailableQuantityAttribute() < ($quantity + $cartQuantity)) {
            throw new \InvalidArgumentException('Product of this quantity is not in stock');
        }

        \DB::transaction(function () use ($variant, $quantity, $cartItem, $meta) {
            if (!$cartItem) {
                $cartItem = new CartItem([
                    'quantity' => $quantity,
                ]);
                $cartItem->cart()->associate($this);
                $cartItem->item()->associate($variant);
                $this->items()->save($cartItem);
                $cartItem->syncMeta($meta);
                $this->save();
            } else {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            }
        }, 5);

        $this->load('items');

        return $this;
    }

    public function remove(ProductVariantContract $variant, ?int $quantity = 1, ?array $meta = []): Cart
    {
        if (! config('ctrlc.cart.allow_remove')) {
            throw new \InvalidArgumentException('Removing items from cart is disabled');
        }

        \DB::transaction(function () use ($variant, $quantity, $meta) {
            $cartItem = $this->getCartItem($variant, $meta);
            if ($cartItem->quantity === $quantity) {
                $cartItem->delete();
            }
            if ($cartItem->quantity >= ($quantity + 1)) {
                $cartItem->quantity -= $quantity;
                $cartItem->save();
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
        return $this->fresh();
    }

    public function create(): Cart
    {
        $cart = new self();
        $cart->save();

        return $cart->fresh();
    }

    public function toJson($options = 0): CartResource
    {
        return new CartResource($this);
    }
}
