<?php declare(strict_types=1);

use Ctrlc\Basket\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['api']], function () {
        Route::prefix('basket')->group(function () {
            Route::get('/get', 'Ctrlc\Basket\Http\Controllers\API\BasketController@getBasket')->name('api.basket.get');
            Route::post('/add', 'Ctrlc\Basket\Http\Controllers\API\BasketController@add')->name('api.basket.add');
            Route::post('/remove', 'Ctrlc\Basket\Http\Controllers\API\BasketController@remove')->name('api.basket.remove');
        });

        Route::resource('/products', ProductController::class)->only(['index', 'show']);
    });
});
