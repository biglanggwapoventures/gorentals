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


Route::get('/', 'HomeController@index');

// Auth::routes();
Route::get('/home', 'HomeController@index');
Route::get('/new-property', 'PropertyOwnerController@createProperty');
Route::get('/profile', 'UserController@profile');
Route::post('/profile', 'UserController@update');
Route::get('/profile/edit', 'UserController@edit');
Route::get('/appointments', 'UserController@appointments');
Route::patch('/appointment/{id}/{flag}', 'UserController@updateAppointment')->name('appointment.update');


Route::post('/appointments/units/{id}', 'UserController@setAppointment')->name('appointment.set');


//Route::get('/appointments/{id}/delete', 'UserController@deleteAppointment');
Route::get('/profile/changepassword', 'UserController@changepassword');
Route::post('/profile/changepassword', 'UserController@changepassword');
Route::get('/notifications', 'NotificationsController@index');
Route::get('/notifications/{partner}', 'NotificationsController@getConversation');
Route::post('/send-message/{partner}', 'NotificationsController@sendMessage');

Route::group(['prefix' => 'admin'], function(){
    Route::get('/', 'AdminController@index');
    Route::get('/users', 'AdminController@users');
    Route::group(['prefix' => 'units'], function(){
        Route::post('{unit}/approve', 'AdminController@approveUnit')->name('approve-unit');
    });
    // Route::post('/', 'AdminController@index');
});
Route::group(['prefix' => 'users'], function(){
    Route::get('/{id}', 'UserController@index');
    // Route::post('/', 'AdminController@index');
});
Route::get('/deleteproperty', 'PropertyOwnerController@deleteProperty');
Route::get('/deleteUnit', 'PropertyOwnerController@deleteUnit');
Route::group(['prefix' => 'properties'], function(){
    Route::post('/', 'PropertyOwnerController@storeProperty');
    Route::get('/', 'PropertyOwnerController@showProperties');
    Route::get('/{id}', 'PropertyOwnerController@editProperty');
    Route::post('/{id}', 'PropertyOwnerController@updateProperty');
    Route::get('/units', 'PropertyOwnerController@showPropertyUnits');
    Route::get('{property}/units/create', 'PropertyOwnerController@createUnit');

    Route::post('{property}/units/{id}/appointments', 'PropertyOwnerController@storeAppointment');
    Route::get('{property}/units/{id}/view', 'PropertyOwnerController@showUnit');
    Route::get('{property}/units/{id}', 'PropertyOwnerController@editUnit');
    Route::post('{property}/units/{id}', 'PropertyOwnerController@updatePropertyUnit');
    Route::get('{property}/units', 'PropertyOwnerController@showPropertyUnits');
    Route::post('{property}/units', 'PropertyOwnerController@storePropertyUnit');
    
    // Route::get('{unit}/view', 'PropertyOwnerController@createUnit');
});

Route::group(['prefix' => 'units'], function(){
    Route::get('{unit}/view', 'UnitController@show')->name('view-unit');
});

Route::group(['prefix' => 'dashboard'], function(){
    Route::get('/', 'DashboardController@index');
});
Route::get('/testcode', 'UnitController@test');
Route::get('/gofavorites', 'UnitController@gofavorites');
Route::get('/addfavorite', 'UnitController@addfavorite');
Route::get('/removefavorite', 'UnitController@removefavorite');
Route::get('/home', 'HomeController@index');
Route::get('/contact', 'ContactController@index');
Route::post('/contact', 'ContactController@postCOntact');
Auth::routes();
Route::post('/register', 'RegisterController');
Route::post('/login', 'LoginController');
Route::get('/logout', 'LogoutController');

