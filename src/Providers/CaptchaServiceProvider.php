<?php

namespace Igoshev\Captcha\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Igoshev\Captcha\Captcha\Captcha;

class CaptchaServiceProvider extends ServiceProvider
{
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

        $this->publishes([__DIR__ . '/../config' => config_path()], 'bone-captcha-config');
        $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang')], 'bone-captcha-lang');
        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views')], 'bone-captcha-views');

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
        $this->app->singleton(Captcha::class, static function (Application $app) {
            $config = $app['config']['bone']['captcha'];

            $storage   = $app->make($config['storage']);
            $generator = $app->make($config['generator']);
            $code      = $app->make($config['code']);

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
        if (! class_exists('\Blade')) {
            return;
        }

        Blade::directive(config('bone.captcha.blade'), static function () {
            return '<?php echo Igoshev\Captcha\Facades\Captcha::getView() ?>';
        });
    }

    /**
     * Register captcha routes.
     */
    protected function registerRoutes()
    {
        $this->app['router']->group([
            'middleware' => config('bone.captcha.middleware', 'web'),
            'namespace'  => 'Igoshev\Captcha\Controllers',
            'as'         => 'bone.captcha.'
        ], static function ($router) {
            $router->get(config('bone.captcha.routes.image'), 'CaptchaController@image')->name('image');
            $router->get(config('bone.captcha.routes.image_tag'), 'CaptchaController@imageTag')->name('image.tag');
        });
    }

    /**
     * Register captcha validator.
     */
    protected function registerValidator()
    {
        Validator::extend(config('bone.captcha.validator'), function ($attribute, $value, $parameters, $validator) {
            return $this->app[Captcha::class]->validate($value);
        }, trans('bone::captcha.incorrect_code'));
    }
}
