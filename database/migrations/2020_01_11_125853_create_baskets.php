<?php declare(strict_types=1);

use Ctrlc\Basket\Models\Basket;
use Ctrlc\Basket\Models\Product;
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
            $table->foreignIdFor(Basket::class)->constrained();
            $table->unsignedMediumInteger('quantity')->default(0);
            $table->morphs('product');
            $table->morphs('variant');
            $table->json('metaData')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->boolean('default')->default(0);
            $table->string('name');
            $table->string('img')->nullable();
            $table->bigInteger('price')->nullable()->unsigned()->comment('NULL = free');
            $table->foreignIdFor(Product::class)->constrained();
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
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('basket_items');
        Schema::dropIfExists('baskets');
    }
}
