<?php

use Illuminate\Support\Facades\Route;

Route::prefix('apple')->group(function () {

    Route::post('purchase/verification', 'MockService\AppleStoreController@verification')
        ->name('api.mock.apple.purchase.verification');

});
