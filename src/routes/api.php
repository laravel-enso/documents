<?php

Route::middleware(['web', 'auth', 'core'])
    ->prefix('api/core')->as('core.')
    ->namespace('LaravelEnso\DocumentsManager\app\Http\Controllers')
    ->group(function () {
        Route::resource('documents', 'DocumentController', [
            'only' => ['store', 'index', 'destroy'],
        ]);
    });
