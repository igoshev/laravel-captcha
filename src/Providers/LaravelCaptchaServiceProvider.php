<?php

namespace Bone\Captcha\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Bone\Captcha\Captcha\Captcha;

class LaravelCaptchaServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->mergeConfigFrom(__DIR__ . '/../config/bone/captcha.php', 'bone');
        $this->loadViewsFrom(__DIR__ . '/../resources/views/vendor/bone', 'bone');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang/vendor/bone', 'bone');

        $this->publishes([__DIR__ .'/../config' => config_path()], 'config');
        $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang')], 'lang');
        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views')], 'views');

        $this->registerBladeDirectives();
        $this->registerRoutes();
        $this->registerTranslations();
        $this->registerValidator();
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
		return ['bone_captcha'];
	}

    /**
     * Register the blade directives
     *
     * @return void
     */
    protected function registerBladeDirectives()
    {
        if (!class_exists('\Blade')) {
            return;
        }

        \Blade::directive('bonecaptcha', function ($expression) {
            return "<?php echo Bone\\Captcha\\Facades\\Captcha::html({$expression}) ?>";
        });
    }

	protected function registerRoutes()
    {
        Route::group([
            'middleware' => config('bone.captcha.middleware', 'web'),
            'namespace' => 'Bone\Captcha\Controllers',
            'prefix' => 'bone/captcha',
            'as' => 'bone.captcha.'
        ], function () {
            Route::get('/', 'CaptchaController@index')->name('image');
            Route::get('html', 'CaptchaController@html')->name('html');
        });
    }

    protected function registerValidator()
    {
        Validator::extend('bone_captcha', function ($attribute, $value, $parameters, $validator) {
            return $this->app['bone_captcha']->validate($value);
        }, trans('bone::captcha.incorrect_code'));
    }
}
