<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth', 'core'])
    ->prefix('api/core/documents')
    ->as('core.documents.')
    ->namespace('LaravelEnso\Documents\Http\Controllers')
    ->group(function () {
        Route::get('', 'Index')->name('index');
        Route::post('', 'Store')->name('store');
        Route::delete('{document}', 'Destroy')->name('destroy');
    });
