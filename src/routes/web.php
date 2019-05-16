<?php

Route::group(['namespace' => 'Dorcas\ModulesDashboard\Http\Controllers', 'middleware' => ['web','auth']], function() {
	Route::get('dashboard-business', 'ModulesDashboardController@business')->name('dashboard-business');
});

?>