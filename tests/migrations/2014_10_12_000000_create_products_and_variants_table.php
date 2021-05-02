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
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('default')->default(0);
            $table->string('name');
            $table->string('img')->nullable();
            $table->integer('quantity')->unsigned();
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
    }
}
