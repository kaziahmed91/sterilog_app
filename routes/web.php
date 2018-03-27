<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->intended('home');
    // view('auth.login');
});

// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Auth::routes();

Route::group(['prefix' => '', 'middleware' =>'auth' ], function () {

    Route::get('/sterilize', 'SterilizeController@index')->name('sterile');
    Route::get('/sterilize/log', 'SterilizeController@viewLog')->name('sterilizeLog');
    Route::post('/sterilize', 'SterilizeController@sterilize');


    Route::get('/spore', 'SporeTestController@index')->name('spore');
    Route::get('/spore/log', 'SporeTestController@log')->name('sporeLog');

    Route::get('/settings', 'SettingsController@index')->name('settings');
    Route::post('/registerEquiptment', 'SettingsController@addSterilizer')->name('register');

    
});

Route::GET('/home', 'HomeController@index')->name('home');
Route::GET('/company_register', 'CompaniesController@register')->name('company_register');
Route::POST('/company_register', 'CompaniesController@registerCompany')->name('company');
Route::GET('admin/home', 'AdminController@index');




// Route::GET('login', 'Admin\LoginController@showLoginForm')->name('admin.login');
// Route::POST('post', 'Admin\LoginController@login');
// ROUTE::POST('admin-password/email', 'Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
// ROUTE::GET('admin-password/reset', 'Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.reset');
// ROUTE::POST('admin-password/reset', 'Admin\ForgotPasswordController@reset');
// ROUTE::GET('admin-password/reset/{token}', 'Admin\ForgotPasswordController@showResetForm')->('admin.password.reset');
