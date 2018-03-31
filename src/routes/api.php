<?php

Route::middleware(['web', 'auth', 'core'])
    ->prefix('api/core')->as('core.')
    ->namespace('LaravelEnso\DocumentsManager\app\Http\Controllers')
    ->group(function () {
        Route::prefix('documents')->as('documents.')
            ->group(function () {
                Route::post('store/{type}/{id}', 'DocumentController@store')
                    ->name('store');
                Route::get('download/{document}', 'DocumentController@download')
                    ->name('download');
            });

        Route::resource('documents', 'DocumentController', ['only' => ['index', 'show', 'destroy']]);
    });
