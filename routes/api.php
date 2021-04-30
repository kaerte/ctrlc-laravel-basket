<?php declare(strict_types=1);

use Ctrlc\Basket\Http\Controllers\API\BasketController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['api']], function () {
        Route::prefix('basket')->group(function () {
            Route::get('/get', [BasketController::class, 'getBasket'])->name('api.basket.get');
            Route::post('/add', [BasketController::class, 'add'])->name('api.basket.add');
            Route::post('/remove', [BasketController::class, 'remove'])->name('api.basket.remove');
        });
    });
});
