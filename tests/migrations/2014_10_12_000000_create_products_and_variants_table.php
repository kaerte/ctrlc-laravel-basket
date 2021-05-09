<?php

declare(strict_types=1);

use Ctrlc\Basket\Tests\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsAndVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedTinyInteger('default')->default(0);
            $table->string('name');
            $table->string('img')->nullable();
            $table->integer('quantity')->unsigned();
            $table->bigInteger('price')->nullable()->unsigned()->comment('NULL = free');
            $table->foreignIdFor(Product::class)->constrained();
        });

        Schema::create('product_variant_options', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('value');
        });

        Schema::create('product_variant_product_variant_option', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('product_variant_id')->index();
            $table->unsignedBigInteger('product_variant_option_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant_product_variant_option');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_variant_options');
        Schema::dropIfExists('products');
    }
}
