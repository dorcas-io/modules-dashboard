<?php

Route::group(['namespace' => 'Dorcas\ModulesDashboard\Http\Controllers', 'prefix' => 'dashboard', 'middleware' => ['web','auth']], function() {
	Route::get('/', 'ModulesDashboardController@business')->name('dashboard');
	Route::get('/setup', 'ModulesDashboardController@welcome_setup')->name('welcome-setup');
	Route::post('/setup', 'ModulesDashboardController@welcome_post');
	Route::post('/features', 'ModulesDashboardController@welcome_features')->name('save-dashboard-features');
	Route::get('/features', 'ModulesDashboardController@welcome_features')->name('save-dashboard-features2');
	Route::get('/overview', 'ModulesDashboardController@welcome_overview')->name('welcome-overview');
	Route::post('/resend-verification', 'ModulesDashboardController@resendVerification');
});

?>