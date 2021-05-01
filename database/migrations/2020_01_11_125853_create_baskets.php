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
            $table->string('name')->nullable();
            $table->nullableMorphs('basketable');
            $table->timestamps();
        });

        Schema::create('basket_items', function (Blueprint $table) {
            $table->id();
            $table->morphs('item');
            $table->foreignIdFor(Basket::class)->constrained();
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
        Schema::dropIfExists('basket_items');
        Schema::dropIfExists('baskets');
    }
}
