<?php 

namespace SherifAI\LaraFawry\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use SherifAI\LaraFawry\Fawry;

class LaraFawryServiceProvider extends ServiceProvider
{
	
	/**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // The publication files to publish
        $this->publishes([__DIR__ . '/../config/fawry.php' => config_path('fawry.php')]);

        // Append the settings
        $this->mergeConfigFrom(__DIR__ . '/../config/fawry.php', 'fawry');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->registerCommands();

        $this->app->bind('fawry',function(){
            return new Fawry();
        });
    }
}