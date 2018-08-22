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
    Route::post('import', 'ContactController@import')->name('contacts.import');
    Route::post('importEgentic', 'ContactController@importEgentic')->name('contacts.import-egentic');
    Route::get('countExported', 'ContactController@countExported')->name('contacts.countExported');
    Route::get('export-to-OLM', 'ContactController@exportToOLM')->name('contacts.export-to-OLM');
    Route::get('count-export-to-OLM', 'ContactController@countContactOLM')->name('contacts.count-export-to-OLM');
});

Route::get('/report', 'ReportController@index')->name('report');
Route::get('/filter', 'ReportController@getReport')->name('report.filter');
Route::get('/export', 'ReportController@exportReport')->name('report.export');
Route::get('/getReportSource', 'ReportController@getReportSource')->name('report.getReportSource');
Route::get('/getReportMonthly', 'ReportController@getReportMonthly')->name('report.get-report-monthly');
Route::get('/exportMonthly', 'ReportController@exportMonthly')->name('report.export-monthly');
Route::get('/getReportYear', 'ReportController@getReportYear')->name('report.get-report-year');
Route::get('/getReportStatistic', 'ReportController@getReportStatistic')->name('report.get-report-statistic');


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
    Route::get('getFilterSource', 'AjaxController@getFilterSource')->name('ajax-getFilterSource');
    Route::get('getFilterTeam', 'AjaxController@getFilterTeam')->name('ajax-getFilterTeam');
    Route::get('getFilterCampaign', 'AjaxController@getFilterCampaign')->name('ajax-getFilterCampaign');
    Route::get('/getC3Chart', 'AjaxController@getC3Chart')->name('ajax-getC3Chart');
    Route::get('/getL8Chart', 'AjaxController@getL8Chart')->name('ajax-getL8Chart');
    Route::get('/paginate', 'AjaxController@getContactPaginate')->name('ajax-paginate');
    Route::get('/getFilterMaketer', 'AjaxController@getFilterMaketer')->name('ajax-getFilterMaketer');
    Route::get('/updateContacts', 'AjaxController@updateContacts')->name('ajax-updateContacts');
    Route::get('/setStatisticChart', 'AjaxController@prepareStatisticChart')->name('ajax-setStatisticChart');
    Route::get('getFilterSubCampaign', 'AjaxController@getFilterSubCampaign')->name('ajax-getFilterSubCampaign');
	Route::get('/getHourC3Chart', 'AjaxController@getHourC3Chart')->name('ajax-getHourC3Chart');
	Route::get('/getHourC3BChart', 'AjaxController@getHourC3BChart')->name('ajax-getHourC3BChart');
	Route::get('/getHourC3BGChart', 'AjaxController@getHourC3BGChart')->name('ajax-getHourC3BGChart');
    Route::get('/get-channel', 'AjaxController@get_channel')->name('get-channel');
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

Route::group(['prefix' => 'config'], function () {

    Route::get('/', 'ConfigController@index')->name('config');
    Route::post('/create', 'ConfigController@store')->name('config-create');
    Route::get('/get/{id}', 'ConfigController@get')->name('config-get');
});

Route::group(['prefix' => 'tracking-inventory'], function () {
    Route::get('/', 'TrackingController@index')->name('tracking-inventory-index');
    Route::get('/show/success/{id}', 'TrackingController@showSuccess')->name('tracking-inventory-success');
    Route::get('/show/duplicate/{id}', 'TrackingController@showDuplicate')->name('tracking-inventory-duplicate');
});

Route::group(['prefix' => 'tracking'], function () {
	Route::get('/double-check', 'TrackingController@doubleCheck')->name('double-check');
	Route::post('/double-check', 'TrackingController@doubleCheck')->name('double-check.filter');
});

Route::group(['prefix' => 'notification'], function () {
	Route::get('/', 'NotificationController@index')->name('notification');
	Route::get('/show/{id}', 'NotificationController@show')->name('notification-show');
	Route::get('/confirm/{id}', 'NotificationController@confirm')->name('notification-confirm');
	Route::get('/get/{id}', 'NotificationController@get')->name('notification-get');
	Route::post('/save', 'NotificationController@save')->name('notification-save');
});

Route::group(['prefix' => 'sub_report'], function () {
    Route::get('/', 'SubReportController@index')->name('sub-report-line');
    Route::get('/getBudget', 'SubReportController@getBudget')->name('get-budget');
    Route::get('/getQuantity', 'SubReportController@getQuantity')->name('get-quantity');
    Route::get('/getQuality', 'SubReportController@getQuality')->name('get-quality');
    Route::get('/getC3AC3B', 'SubReportController@getC3AC3B')->name('get-C3AC3B');
    Route::get('/getBudgetTOT', 'SubReportTOTController@getBudget')->name('get-budget-tot');
    Route::get('/getQuantityTOT', 'SubReportTOTController@getQuantity')->name('get-quantity-tot');
    Route::get('/getQualityTOT', 'SubReportTOTController@getQuality')->name('get-quality-tot');

    Route::get('/line-chart-filter', 'SubReportController@getFilter')->name('line-chart.filter');
	Route::get('/channel-report', 'SubReportController@channelReport')->name('channel-report');
	Route::get('/channel-report-filter', 'SubReportController@channelReportFilter')->name('channel-report.filter');
	Route::get('/hour-report', 'SubReportController@hourReport')->name('hour-report');
	Route::post('/hour-report', 'SubReportController@hourReportFilter')->name('hour-report.filter');

    Route::get('/getByDays', 'SubReportController@getDataByDays')->name('line-chart.getByDays');
    Route::get('/getByWeeks', 'SubReportController@getDataByWeeks')->name('line-chart.getByWeeks');
    Route::get('/getByMonths', 'SubReportController@getDataByMonths')->name('line-chart.getByMonths');

    Route::get('/getTOTByDays', 'SubReportTOTController@getDataTOTByDays')->name('line-chart.getTOTByDays');
    Route::get('/getTOTByWeeks', 'SubReportTOTController@getDataTOTByWeeks')->name('line-chart.getTOTByWeeks');
    Route::get('/getTOTByMonths', 'SubReportTOTController@getDataTOTByMonths')->name('line-chart.getTOTByMonths');

    Route::get('/assign-kpi', 'KpiController@assign_kpi')->name('assign-kpi');
    Route::get('/get-kpi', 'KpiController@get_kpi')->name('get-kpi');
    Route::get('/save-kpi', 'KpiController@save_kpi')->name('save-kpi');
    Route::get('/kpi-by-maketer', 'KpiController@kpi_by_maketer')->name('kpi-by-maketer');
    Route::get('/kpi-by-team', 'KpiController@kpi_by_team')->name('kpi-by-team');

});

Route::group(['prefix' => 'thankyou_page'], function () {
    Route::get('/', 'ThankYouPageController@index')->name('thankyou-page');
    Route::post('/create', 'ThankYouPageController@store')->name('thankyou-page-create');
    Route::get('/get/{id}', 'ThankYouPageController@get')->name('thankyou-page-get');
});

Route::group(['prefix' => 'channel'], function () {
    Route::get('/', 'ChannelController@index')->name('channel');
    Route::post('/create', 'ChannelController@store')->name('channel-create');
    Route::get('/get/{id}', 'ChannelController@get')->name('channel-get');
    Route::get('/get-all', 'ChannelController@getAllChannel')->name('channel-get-all');
});

Route::group(['prefix' => 'diff-contacts'], function () {
    Route::get('/', 'DiffContactsController@index')->name('diff-contacts');
    Route::get('/filter', 'DiffContactsController@filter')->name('diff-contacts.filter');
});

Route::group(['prefix' => 'inventory-report'], function () {
    Route::get('/', 'InventoryReportController@index')->name('inventory-report');
});
