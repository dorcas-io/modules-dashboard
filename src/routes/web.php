<?php

Route::group(['namespace' => 'Dorcas\ModulesDashboardBusiness\Http\Controllers', 'middleware' => ['web']], function() {
    Route::get('sales', 'ModulesDashboardBusinessController@index')->name('sales');
});


Route::get('/home', 'HomeController@index')->name('home');

?>