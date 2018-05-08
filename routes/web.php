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


Route::group(['prefix' => '', 'middleware' => ['auth', 'softUser'] ], function () {

    
    Route::GET('/home', 'HomeController@index')->name('home');
    // Route::post('/sterilize', 'SterilizeController@sterilize');
    Route::post('/sterilize', 'SterilizeController@sterilize');
    Route::get('/sterilize', 'SterilizeController@index')->name('sterile');
    Route::get('/sterilize/log', 'SterilizeController@viewLog')->name('sterile.logs');
    
    Route::post('/sterilize/filter', 'SterilizeController@filter');
    Route::get('/sterilize/filter', 'SterilizeController@filter');
    Route::get('/privateKey', 'SterilizeController@getPrivateKey');
    Route::post('/deletePdf', 'SterilizeController@deletePdf');
    

    Route::post('/updateCycle', 'SterilizeController@updateCycle');
    Route::post('/signSignature', 'SterilizeController@signSignature');


    Route::get('/spore', 'SporeTestController@index')->name('spore');
    Route::get('/spore/log', 'SporeTestController@log')->name('spore.logs');
    // Route::get('/spore/new', 'SporeTestController@createSporeTest')->name('spore.new');
    Route::post('/spore/new', 'SporeTestController@createSporeTest');
    Route::post('/spore/update', 'SporeTestController@updateSporeTest');
    Route::post('/spore/update/comment', 'SporeTestController@updateSporeComment');
    Route::get('/spore/log/filter', 'SporeTestController@filter');
    Route::post('/spore/log/filter', 'SporeTestController@filter');
    
});

Route::group(['prefix' => '', 'middleware' => ['auth'] ], function () {
    Route::get('/user/login', 'SoftUserController@index')->name('user.login');
    Route::get('/user/logout', 'SoftUserController@logout' )->name('user.logout');
    Route::post('/user/login', 'SoftUserController@login' );
});



Route::group(['prefix' => 'settings', 'middleware' => 'auth'], function () {

    Route::get('/', function(){
        return redirect()->route('settings.users');
    });

    Route::get('/users','SettingsController@getUsersView')->name('settings.users');
    Route::get('/user/{id}','SettingsController@getUserView')->name('settings.user');
    Route::post('/user/{id}/edit', 'SettingsController@editSoftUser')->name('settings.user.edit');
    Route::post('/user/{id}/password','SettingsController@changeSoftUserPassword' )->name('settings.user.password');

    Route::get('/equiptment','SettingsController@getEquiptmentView')->name('settings.equiptment');
    Route::get('/cleaners','SettingsController@getCleanersView')->name('settings.cleaners');
    Route::get('/company','SettingsController@getCompanyView')->name('settings.company');
    
    Route::post('/register/equiptment', 'SettingsController@addSterilizer');
    Route::post('/register/equiptment/remove', 'SettingsController@removeSterilizer');
    Route::post('/register/cleaners', 'SettingsController@addCleaner');
    Route::post('/register/cleaners/remove', 'SettingsController@removeCleaner');
    
    Route::post('/register/company/printer', 'SettingsController@editPrinter')->name('settings.printer.edit');
    Route::post('/register/user','SettingsController@addSoftUser' )->name('settings.user.register');

    // Route::post('/register/user/edit/{id}','SettingsController@addUser' )->name('settings.user.add');
});

Route::GET('/company_register', 'CompaniesController@register')->name('company_register');
Route::POST('/company_register', 'CompaniesController@registerCompany')->name('company');
