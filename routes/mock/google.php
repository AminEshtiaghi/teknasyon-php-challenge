<?php

use Illuminate\Support\Facades\Route;

Route::prefix('google')->group(function () {

    Route::post('purchase/verification', 'MockService\GooglePlayController@verification')
        ->name('api.mock.google.purchase.verification');

});