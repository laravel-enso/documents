<?php

Route::middleware(['signed', 'bindings'])
    ->prefix('api/core/documents')->as('core.documents.')
    ->namespace('LaravelEnso\DocumentsManager\app\Http\Controllers')
    ->group(function () {
        Route::get('share/{document}', 'DocumentController@share')
            ->name('share');
    });

Route::middleware(['web', 'auth', 'core'])
    ->prefix('api/core')->as('core.')
    ->namespace('LaravelEnso\DocumentsManager\app\Http\Controllers')
    ->group(function () {
        Route::prefix('documents')->as('documents.')
            ->group(function () {
                Route::post('store/{type}/{id}', 'DocumentController@store')
                    ->name('store');
                Route::get('link/{document}', 'DocumentController@link')
                    ->name('link');
                Route::get('download/{document}', 'DocumentController@download')
                    ->name('download');
            });

        Route::resource('documents', 'DocumentController', ['only' => ['index', 'show', 'destroy']]);
    });
