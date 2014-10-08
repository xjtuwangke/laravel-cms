<?php namespace Xjtuwangke\LaravelCms;

use Illuminate\Support\ServiceProvider;
use Xjtuwangke\LaravelCms\Elements\Form\KValidator;

class LaravelCmsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package( 'xjtuwangke/laravel-cms' , 'laravel-cms' );
        \Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new KValidator($translator, $data, $rules, $messages);
        });
        include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
