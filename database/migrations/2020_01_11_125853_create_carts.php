<?php

declare(strict_types=1);

use Ctrlc\Cart\EloquentCart;
use Ctrlc\DiscountCode\Models\DiscountCode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->nullableMorphs('cartable');
            $table->foreignIdFor(DiscountCode::class)->nullable()->constrained();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->morphs('item');
            $table->foreignIdFor(EloquentCart::class, 'cart_id')
                ->constrained()
                ->onDelete('cascade');
            $table->unsignedMediumInteger('quantity')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
}
