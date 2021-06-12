<?php

declare(strict_types=1);

use Ctrlc\Basket\Models\Basket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaskets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
            $table->boolean('locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->nullableMorphs('basketable');
        });

        Schema::create('basket_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->morphs('item');
            $table->foreignIdFor(Basket::class)->constrained();
            $table->unsignedMediumInteger('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('basket_items');
        Schema::dropIfExists('baskets');
    }
}
