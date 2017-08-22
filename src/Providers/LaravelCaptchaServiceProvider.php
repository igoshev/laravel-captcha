<?php

namespace Bone\Captcha\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
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
        $this->mergeConfigFrom(__DIR__ . '/../config/bone/captcha.php', 'bone.captcha');
        $this->loadViewsFrom(__DIR__ . '/../resources/views/vendor/bone', 'bone');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang/vendor/bone', 'bone');

        $this->publishes([__DIR__ . '/../config' => config_path()], 'config');
        $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang')], 'lang');
        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views')], 'views');

        $this->registerRoutes();
        $this->registerBladeDirectives();
        $this->registerValidator();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bone_captcha', function (Application $app) {
            $config = $app['config']['bone']['captcha'];

            $storage = $app->make($config['storage']);
            $generator = $app->make($config['generator']);
            $code = $app->make($config['code']);

            return new Captcha($code, $storage, $generator, $config);
        });
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

        Blade::directive('bonecaptcha', function () {
            return "<?php echo Bone\\Captcha\\Facades\\Captcha::getView() ?>";
        });
    }

    /**
     * Register captcha routes.
     */
    protected function registerRoutes()
    {
        $this->app['router']->group([
            'middleware' => config('bone.captcha.middleware', 'web'),
            'namespace' => 'Bone\Captcha\Controllers',
            'as' => 'bone.captcha.'
        ], function ($router) {
            $router->get(config('bone.captcha.routes.image'), 'CaptchaController@image')->name('image');
            $router->get(config('bone.captcha.routes.image_tag'), 'CaptchaController@imageTag')->name('image.tag');
        });
    }

    /**
     * Register captcha validator.
     */
    protected function registerValidator()
    {
        Validator::extend('bone_captcha', function ($attribute, $value, $parameters, $validator) {
            return $this->app['bone_captcha']->validate($value);
        }, trans('bone::captcha.incorrect_code'));
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
}
