<?php

Route::group(['namespace' => 'Dorcas\ModulesDashboard\Http\Controllers', 'prefix' => 'dashboard', 'middleware' => ['web','auth']], function() {
	Route::get('/', 'ModulesDashboardController@business')->name('dashboard');
	Route::get('/setup', 'ModulesDashboardController@welcome_setup')->name('welcome-setup');
	Route::post('/setup', 'ModulesDashboardController@welcome_post');
	Route::get('/overview', 'ModulesDashboardController@welcome_overview')->name('welcome-overview');
});

?>