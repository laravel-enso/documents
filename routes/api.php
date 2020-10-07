<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\Documents\Http\Controllers\Index;
use LaravelEnso\Documents\Http\Controllers\Store;
use LaravelEnso\Documents\Http\Controllers\Destroy;

Route::middleware(['api', 'auth', 'core'])
    ->prefix('api/core/documents')
    ->as('core.documents.')
    ->group(function () {
        Route::get('', Index::class)->name('index');
        Route::post('', Store::class)->name('store');
        Route::delete('{document}', Destroy::class)->name('destroy');
    });
