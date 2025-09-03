<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;




Route::group([
    'prefix' => LaravelLocalization::setLocale() . '/panel',
    'middleware' => [
        'localeSessionRedirect', 
        'localizationRedirect', 
        'localeViewPath'
    ]
], function () {

    Route::get('/dashboarddd', function () {
        return "This is the panel dashboard.";
    });

});
