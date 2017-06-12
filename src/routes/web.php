<?php

Route::group([
	'namespace' => 'LaravelEnso\DocumentsManager\app\Http\Controllers',
	'middleware' => ['web', 'auth', 'core']
], function () {
    Route::group(['prefix' => 'core/documents', 'as' => 'core.documents.'], function () {
        Route::post('upload', 'DocumentController@upload')->name('upload');
        Route::get('', 'DocumentController@index')->name('index');
        Route::get('show/{document}', 'DocumentController@show')->name('show');
        Route::delete('destroy/{document}', 'DocumentController@destroy')->name('destroy');
        Route::get('download/{document}', 'DocumentController@download')->name('download');
    });
});
