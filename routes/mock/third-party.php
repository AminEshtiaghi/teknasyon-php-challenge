<?php

use Illuminate\Support\Facades\Route;

Route::prefix('third-party')->group(function () {

    Route::post('status/change', 'MockService\ThirdPartyController@changeStatus')
        ->name('api.mock.third-party.status.change');

});
