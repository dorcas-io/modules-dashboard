<?php

Route::group(['namespace' => 'Dorcas\ModulesDashboard\Http\Controllers', 'middleware' => ['web','auth']], function() {
	Route::get('dashboard', 'ModulesDashboardController@business')->name('dashboard');
});

?>