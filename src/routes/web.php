<?php

Route::group(['namespace' => 'LaravelEnso\DocumentsManager\app\Http\Controllers', 'middleware' => ['web', 'auth', 'core']], function () {
    Route::group(['prefix' => 'core/documents', 'as' => 'core.documents.'], function () {
        Route::post('upload', 'DocumentsController@upload')->name('upload');
        Route::get('list', 'DocumentsController@list')->name('list');
        Route::get('show/{document}', 'DocumentsController@show')->name('show');
        Route::delete('destroy/{document}', 'DocumentsController@destroy')->name('destroy');
        Route::get('download/{document}', 'DocumentsController@download')->name('download');
    });
});
