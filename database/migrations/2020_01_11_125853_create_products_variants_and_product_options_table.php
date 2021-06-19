<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsVariantsAndProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->morphs('productable');
            $table->unsignedTinyInteger('default')->default(0);
            $table->string('name');
            $table->string('img')->nullable();
            $table->integer('quantity')->unsigned()->nullable();
            $table->bigInteger('price')->nullable()->unsigned()->comment('NULL = free');
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
    }
}
