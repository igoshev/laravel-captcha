<?php

namespace LaravelCaptcha\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use LaravelCaptcha\Captcha\Captcha;

class LaravelCaptchaServiceProvider extends ServiceProvider
{
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
		include __DIR__ . '/../routes.php';

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'bone_captcha');

        Validator::extend('bone_captcha', function ($attribute, $value, $parameters, $validator) {
            return $this->app['bone_captcha']->validate($value);
        }, trans('bone_captcha::trans.incorrect_code'));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->singleton('bone_captcha', function ($app) {
            return new Captcha();
        });
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
