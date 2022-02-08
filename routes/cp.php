<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\LuckyMedia\BusinessHours\Http\Controllers')
    ->prefix('business-hours/')
    ->name('luckymedia.businesshours.')
    ->group(function () {
    Route::get('/', 'BusinessHoursController@index')->name('index');
    Route::put('/', 'BusinessHoursController@update')->name('update');
});
