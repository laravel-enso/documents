<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\Documents\Http\Controllers\Destroy;
use LaravelEnso\Documents\Http\Controllers\Index;
use LaravelEnso\Documents\Http\Controllers\Store;

Route::middleware(['api', 'auth', 'core'])
    ->prefix('api/core/documents')
    ->as('core.documents.')
    ->group(function () {
        Route::get('', Index::class)->name('index');
        Route::post('', Store::class)->name('store');
        Route::delete('{document}', Destroy::class)->name('destroy');
    });
