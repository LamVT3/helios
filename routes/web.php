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

Route::group(['prefix' => 'source'], function () {
    Route::get('/', 'SourceController@index')->name('source');
    Route::post('/create', 'SourceController@store')->name('source-create');
    Route::get('/get/{id}', 'SourceController@get')->name('source-get');
});

Route::group(['prefix' => 'team'], function () {
    Route::get('/', 'TeamController@index')->name('team');
    Route::post('/create', 'TeamController@store')->name('team-create');
    Route::get('/get/{id}', 'TeamController@get')->name('team-get');
});

Route::get('/kpis', 'KpiController@index')->name('kpis');

Route::group(['prefix' => 'campaign'], function () {
    Route::get('/', 'CampaignController@index')->name('campaigns');
    Route::post('/create', 'CampaignController@store')->name('campaign-create');
    Route::get('/get/{id}', 'CampaignController@get')->name('campaign-get');
    Route::get('/{id}', 'CampaignController@show')->name('campaign-details');
});

Route::group(['prefix' => 'landingpage'], function () {

    Route::get('/', 'LandingPageController@index')->name('landing-page');
    Route::post('/create', 'LandingPageController@store')->name('landing-page-create');
    Route::get('/get/{id}', 'LandingPageController@get')->name('landing-page-get');
});

Route::group(['prefix' => 'subcampaign'], function () {
    Route::post('/create', 'SubcampaignController@store')->name('subcampaign-create');
    Route::get('/get/{id}', 'SubcampaignController@get')->name('subcampaign-get');
    Route::get('/{id}', 'SubcampaignController@show')->name('subcampaign-details');
});

Route::group(['prefix' => 'ad'], function () {
    Route::post('/create', 'AdController@store')->name('ads-create');
    Route::get('/get/{id}', 'AdController@get')->name('ads-get');
});

Route::group(['prefix' => 'contacts'], function () {
    Route::get('/', 'ContactController@index')->name('contacts-c3');
    Route::get('filter', 'ContactController@getC3')->name('contacts.filter');
    Route::get('export', 'ContactController@export')->name('contacts.export');
    Route::get('getContactsSource', 'ContactController@getContactsSource')->name('contacts.getContactsSource');
    Route::get('getContactsTeam', 'ContactController@getContactsTeam')->name('contacts.getContactsTeam');
    Route::get('getContactsCampaings', 'ContactController@getContactsCampaings')->name('contacts.getContactsCampaings');
    Route::post('import', 'ContactController@import')->name('contacts.import');
});

Route::get('/report', 'ReportController@index')->name('report');
Route::get('/filter', 'ReportController@getReport')->name('report.filter');
Route::get('/export', 'ReportController@exportReport')->name('report.export');
Route::get('/getReportSource', 'ReportController@getReportSource')->name('report.getReportSource');

/*Route::get('/kpi/add', 'KpiController@add')->name('kpi-add');
Route::get('/policy/edit', 'PolicyController@edit')->name('policy-edit');
Route::get('/inventory', 'InventoryController@index')->name('inventory');
Route::get('/productivity', 'ProductivityController@index')->name('productivity');*/


Route::group(['prefix' => 'ajax'], function () {
    Route::get('/getTeamsCampaigns/{id}', 'AjaxController@getTeamsCampaigns')->name('ajax-getTeamsCampaigns');
    Route::get('/getCampaigns/{id}', 'AjaxController@getCampaigns')->name('ajax-getCampaigns');
    Route::get('/getSubcampaigns/{id}', 'AjaxController@getSubcampaigns')->name('ajax-getSubcampaigns');
    Route::get('/contactDetails/{id}', 'AjaxController@contactDetails')->name('contact-details');
    Route::get('/dashboard', 'AjaxController@dashboard')->name('ajax-dashboard');
    Route::get('/c3_leaderboard', 'AjaxController@c3_leaderboard')->name('ajax-c3-leaderboard');
    Route::get('/revenue_leaderboard', 'AjaxController@revenue_leaderboard')->name('ajax-revenue-leaderboard');
    Route::get('/spent_leaderboard', 'AjaxController@spent_leaderboard')->name('ajax-spent-leaderboard');
});

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

Route::group(['prefix' => 'profile'], function () {
    Route::get('/', 'UserController@profile')->name('profile');
    Route::get('/{username}', 'UserController@profile')->name('profile-user');
});

Route::post('{related}/{model}/delete', 'Controller@deleteRelated')->name('delete-related');
Route::post('{model}/delete', 'Controller@delete')->name('delete');
