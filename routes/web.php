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

// Auth::routes() -> ;

        $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
        $this->post('login', 'Auth\LoginController@login');
        $this->post('logout', 'Auth\LoginController@logout')->name('logout');

        // Registration Routes...
        $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        $this->post('register', 'Auth\RegisterController@register');

        // Password Reset Routes...
        $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        $this->post('password/reset', 'Auth\ResetPasswordController@reset');


Route::group(['prefix' => '', 'middleware' =>'auth' ], function () {

    Route::get('/sterilize', 'SterilizeController@index')->name('sterile');
    Route::get('/sterilize/log', 'SterilizeController@viewLog')->name('sterilizeLog');
    
    // Route::post('/sterilize', 'SterilizeController@sterilize');
    Route::post('/sterilize', 'SterilizeController@sterilize');
    Route::post('/sterilize/filter', 'SterilizeController@filter');
    Route::get('/sterilize/filter', 'SterilizeController@filter');
    Route::get('/privateKey', 'SterilizeController@getPrivateKey');
    Route::post('/deletePdf', 'SterilizeController@deletePdf');
    

    Route::post('/cycleChanges', 'SterilizeController@logChanges');
    
    Route::post('/signSignature', 'SterilizeController@signSignature');


    Route::get('/spore', 'SporeTestController@index')->name('spore');
    Route::get('/spore/log', 'SporeTestController@log')->name('spore.logs');
    // Route::get('/spore/new', 'SporeTestController@createSporeTest')->name('spore.new');
    Route::post('/spore/new', 'SporeTestController@createSporeTest');
    Route::post('/spore/update', 'SporeTestController@updateSporeTest');



    Route::GET('/home', 'HomeController@index')->name('home');
    
});


Route::group(['prefix' => 'settings', 'middleware' =>'auth'], function () {

    Route::get('/', function(){
        return redirect()->route('settings.user');
    });
    Route::get('/user','SettingsController@getUserView')->name('settings.user');
    Route::get('/equiptment','SettingsController@getEquiptmentView')->name('settings.equiptment');
    Route::get('/cleaners','SettingsController@getCleanersView')->name('settings.cleaners');
    Route::get('/company','SettingsController@getCompanyView')->name('settings.company');
    
    Route::post('/settings/register/equiptment', 'SettingsController@addSterilizer');
    Route::post('/settings/register/cleaner', 'SettingsController@addCleaner');
});

Route::GET('/company_register', 'CompaniesController@register')->name('company_register');
Route::POST('/company_register', 'CompaniesController@registerCompany')->name('company');




// Route::GET('login', 'Admin\LoginController@showLoginForm')->name('admin.login');
// Route::POST('post', 'Admin\LoginController@login');
// ROUTE::POST('admin-password/email', 'Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
// ROUTE::GET('admin-password/reset', 'Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.reset');
// ROUTE::POST('admin-password/reset', 'Admin\ForgotPasswordController@reset');
// ROUTE::GET('admin-password/reset/{token}', 'Admin\ForgotPasswordController@showResetForm')->('admin.password.reset');
