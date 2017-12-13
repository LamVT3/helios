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

Route::get('/', 'DashboardController@index')->middleware('auth');

Route::get('login', 'HomeController@index')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'DashboardController@index')->name('dashboard');

Route::group(['prefix' => 'adsmanager'], function () {
    Route::get('/', 'AdsManagerController@index')->name('campaign');
    Route::get('/landingpage', 'AdsManagerController@landingpage')->name('landing-page');
    Route::post('/landingpage/create', 'LandingPageController@store')->name('landing-page-create');
    Route::get('/landingpage/get/{id}', 'LandingPageController@get')->name('landing-page-get');
    Route::post('/campaign/create', 'CampaignController@store')->name('campaign-create');
    Route::get('/campaign/get/{id}', 'CampaignController@get')->name('campaign-get');
    Route::get('/campaign/{id}', 'AdsManagerController@campaign')->name('campaign-details');
    Route::post('/channel/create', 'ChannelController@store')->name('channel-create');
    Route::get('/channel/get/{id}', 'ChannelController@get')->name('channel-get');
    Route::get('/channel/{id}', 'AdsManagerController@channel')->name('channel-details');
    Route::post('/ads/create', 'AdsController@store')->name('ads-create');
    Route::get('/ads/get/{id}', 'AdsController@get')->name('ads-get');
});

/*Route::get('/kpi/add', 'KpiController@add')->name('kpi-add');
Route::get('/policy/edit', 'PolicyController@edit')->name('policy-edit');
Route::get('/inventory', 'InventoryController@index')->name('inventory');
Route::get('/productivity', 'ProductivityController@index')->name('productivity');*/


Route::get('/test', 'Test@index')->name('test');

// USERS
Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'UserController@index')->name('users');
    Route::get('/create', 'UserController@create')->name('users-create');
    Route::get('/edit/{id}', 'UserController@edit')->name('users-edit');
    Route::post('/save', 'UserController@store')->name('users-save');

    Route::get('/roles', 'UserController@roles')->name('users-roles');
    Route::get('/roles/create', 'UserController@roleCreate')->name('users-roles-create');
    Route::get('/roles/edit/{id}', 'UserController@roleEdit')->name('users-roles-edit');
    Route::post('/roles/save', 'UserController@roleStore')->name('users-roles-save');
});

Route::get('/profile', 'UserController@profile')->name('profile');

Route::post('{related}/{model}/delete', 'Controller@deleteRelated')->name('delete-related');
Route::post('{model}/delete', 'Controller@delete')->name('delete');
