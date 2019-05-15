<?php

Route::group(['namespace' => 'Dorcas\ModulesDashboardBusiness\Http\Controllers', 'middleware' => ['web','auth']], function() {
	Route::get('dashboard-business', 'ModulesDashboardBusinessController@business')->name('dashboard-business');
});

?>