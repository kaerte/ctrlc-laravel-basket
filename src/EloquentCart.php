<?php

declare(strict_types=1);

namespace Ctrlc\Cart;

use Ctrlc\Cart\Database\Factories\CartFactory;
use Ctrlc\Cart\Resources\CartResource;
use Ctrlc\DiscountCode\Enums\DiscountCodeTypeEnum;
use Ctrlc\DiscountCode\Models\DiscountCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EloquentCart extends Model implements Cart
{
    use HasFactory;
    
    protected $table = 'carts';

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['total', 'discounted_amount'];

    protected $with = ['cartable', 'discountCode'];

    public function instance(): self
    {
        return $this;
    }

    public function cartable(): MorphTo
    {
        return $this->morphTo();
    }

    public function items(): HasMany
    {
        return $this->hasMany(EloquentCartItem::class, 'cart_id', 'id');
    }

    public function total(): int
    {
        return $this->getTotalAttribute();
    }
    
    public function getTotalAttribute($ignoreDiscount = false): int
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->price * $item->quantity;
        }
        
        if (!$ignoreDiscount) {
            if ($this->discountCode && $this->discountCode->isActive()) {
                if ($this->discountCode->type->equals(DiscountCodeTypeEnum::PERCENT())) {
                    $total -= $total * ($this->discountCode->value / 100);
                }
                if ($this->discountCode->type->equals(DiscountCodeTypeEnum::MONEY())) {
                    $total -= $this->discountCode->value;
                }
            }
        }
        
        //dealing with cents, so round up cent fraction
        return (int) max(round($total), 0);
    }
    
    public function add(CartItemable $variant, ?int $quantity = 1, ?array $meta = []): self
    {
        $this->fresh('items.item');
        $cartItem = $this->getCartItem($variant, $meta);
        $cartQuantity = (int) ($cartItem?->quantity ?? 0);

        if ($variant->getAvailableQuantityAttribute() && $variant->getAvailableQuantityAttribute() < ($quantity + $cartQuantity)) {
            throw new \InvalidArgumentException('Product of this quantity is not in stock');
        }

        \DB::transaction(function () use ($variant, $quantity, $cartItem, $meta) {
            if (!$cartItem) {
                $cartItem = new EloquentCartItem([
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

    public function remove(CartItemable $variant, ?array $meta = []): self
    {
        if (! config('ctrlc.cart.allow_remove')) {
            throw new \InvalidArgumentException('Removing items from cart is disabled');
        }

        \DB::transaction(function () use ($variant, $meta) {
            $cartItem = $this->getCartItem($variant, $meta);
            $cartItem->delete();
        }, 5);

        $this->load('items');

        return $this;
    }

    public function updateQuantity(CartItemable $variant, ?int $quantity = 1, ?array $meta = []): self
    {
        $this->fresh('items.item');
        $cartItem = $this->getCartItem($variant, $meta);
        if (!$cartItem) {
            throw new \InvalidArgumentException('Cart item for update quantity not found');
        }
        
        if ($quantity === 0) {
            return $this->remove($variant, $meta);
        }

        if ($variant->getAvailableQuantityAttribute() && $variant->getAvailableQuantityAttribute() < ($quantity)) {
            throw new \InvalidArgumentException('Product of this quantity is not in stock');
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        $this->load('items');

        return $this;
    }

    private function getCartItem(CartItemable $variant, array $meta)
    {
        return $this->items()
            ->where('item_id', $variant->getKey())
            ->where('item_type', $variant::class)
            ->when($meta, function (Builder $query, $meta) {
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

    public function get(): self
    {
        return $this->fresh();
    }

    public function create(): self
    {
        $cart = new self();
        $cart->save();

        return $cart->fresh();
    }

    public function toJson($options = 0): CartResource
    {
        return new CartResource($this);
    }

    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function addDiscountCode($discountCode): self
    {
        $this->discountCode()->associate($discountCode);
        $this->save();
     
        return $this;
    }
    
    public function removeDiscountCode(): self
    {
        $this->discountCode()->disassociate();
        $this->save();

        return $this;
    }

    public function discountedAmount(): int
    {
        return $this->getDiscountedAmountAttribute();
    }
    
    public function getDiscountedAmountAttribute(): int
    {
        return $this->getTotalAttribute(true) - $this->getTotalAttribute();
    }
}
