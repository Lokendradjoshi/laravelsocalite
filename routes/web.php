<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where the routes are registered for our application.
|
*/

// Named route required for SendsPasswordResetEmails.
Route::get('reset-password', function() {
    return view('index');
})->name('password.reset');

Route::get('/redirect/{social}','Auth\LoginController@socialLogin')->where('social','facebook|google');


// Catches all other web routes.
Route::get('{slug}', function () {
    return view('index');
})->where('slug', '^(?!api).*$');
