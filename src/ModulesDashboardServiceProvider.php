<?php

namespace Dorcas\ModulesDashboard;
use Illuminate\Support\ServiceProvider;
use Dorcas\ModulesDashboard\Classes\PartnerSetupCommand;

class ModulesDashboardServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->loadRoutesFrom(__DIR__.'/routes/web.php');
		$this->loadViewsFrom(__DIR__.'/resources/views', 'modules-dashboard');
		$this->publishes([
			__DIR__.'/config/modules-dashboard.php' => config_path('modules-dashboard.php'),
		], 'dorcas-modules');
		/*$this->publishes([
			__DIR__.'/assets' => public_path('vendor/modules-dashboard')
		], 'dorcas-modules');*/

        if ($this->app->runningInConsole()) {
            $this->commands([
                PartnerSetupCommand::class,
            ]);
        }
	}

	public function register()
	{
		//add menu config
		$this->mergeConfigFrom(
	        __DIR__.'/config/navigation-menu.php', 'navigation-menu.modules-dashboard.sub-menu'
	     );
	}

}


?>