<?php declare(strict_types=1);

namespace Ctrlc\Basket\Models;

use Ctrlc\Basket\Contracts\BasketItemVariantContract;
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
        foreach ($this->items as $i) {
            if ($i->product) {
                $total += $i->product->price * $i->quantity;
            }
        }

        return $total;
    }

    public function total(): float|int
    {
        return $this->getTotalAttribute();
    }

    public function add(BasketItemVariantContract $productVariant, ?int $quantity = 1): Basket
    {
        //todo check if product can be added to basket

        \DB::transaction(function () use ($productVariant, $quantity) {
            $this->fresh('items.product');
            $productVariant->load('item');

            $basketItem = $this->findBasketItemFromVariant($productVariant);

            if ($basketItem == null) {
                $basketItem = new BasketItem([
                    'quantity' => $quantity,
                ]);
                $basketItem->basket()->associate($this);
                $basketItem->product()->associate($productVariant->item);
                $basketItem->variant()->associate($productVariant);
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

    public function remove(BasketItemVariantContract $productVariant, ?int $quantity = 1): Basket
    {
        if (!config('ctrlc.basket.allow_remove')) {
            throw new \InvalidArgumentException('Removing items from basket is disabled');
        }

        //todo validate quantity

        \DB::transaction(function () use ($productVariant, $quantity) {
            $basketItem = $this->findBasketItemFromVariant($productVariant);
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

    private function findBasketItemFromVariant(BasketItemVariantContract $productVariant)
    {
        return $this->items()
            ->where('product_id', $productVariant->item->id)
            ->where('product_type', $productVariant->item::class)
            ->where('variant_id', $productVariant->id)
            ->where('variant_type', $productVariant::class)
            ->limit(1)
            ->get()
            ->first();
    }

    protected static function newFactory(): BasketFactory
    {
        return BasketFactory::new();
    }
}
