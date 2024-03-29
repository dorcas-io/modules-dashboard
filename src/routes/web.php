<?php

Route::group(['namespace' => 'Dorcas\ModulesDashboard\Http\Controllers', 'prefix' => 'dashboard', 'middleware' => ['web','auth']], function() {
	Route::get('/', 'ModulesDashboardController@business')->name('dashboard');
	Route::get('/setup', 'ModulesDashboardController@welcome_setup')->name('welcome-setup');
	Route::post('/setup', 'ModulesDashboardController@welcome_post')->name('welcome-setup-post');
	Route::post('/features', 'ModulesDashboardController@welcome_features')->name('save-dashboard-features');
	Route::get('/overview', 'ModulesDashboardController@welcome_overview')->name('welcome-overview');
	Route::post('/resend-verification', 'ModulesDashboardController@resendVerification');
	Route::get('/faqs', 'ModulesDashboardController@faqs');

    Route::get('fetch-token' , 'ModulesDashboardController@fetchToken');


	Route::post('/process-dashboard', 'ModulesDashboardController@processDashboard')->name('process-dashboard');

	Route::get('/customization-setup', 'ModulesDashboardController@customization_setup')->name('customization-setup-post');

});

?>