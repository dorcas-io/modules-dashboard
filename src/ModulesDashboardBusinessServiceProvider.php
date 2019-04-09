<?php

namespace Dorcas\ModulesDashboardBusiness;
use Illuminate\Support\ServiceProvider;

class ModulesDashboardBusinessServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->loadRoutesFrom(__DIR__.'/routes/web.php');
		$this->loadViewsFrom(__DIR__.'/resources/views', 'modules-dashboard-business');
		$this->publishes([
			__DIR__.'/config/modules-dashboard-business.php' => config_path('modules-dashboard-business.php'),
		], 'config');
		/*$this->publishes([
			__DIR__.'/assets' => public_path('vendor/modules-dashboard-business')
		], 'public');*/
	}

	public function register()
	{
		//add menu config
		$this->mergeConfigFrom(
	        __DIR__.'/config/navigation-menu.php', 'navigation-menu.modules-dashboard-business.sub-menu'
	     );
	}

}


?>