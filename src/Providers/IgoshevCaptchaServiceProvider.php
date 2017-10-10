<?php

namespace Igoshev\Captcha\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Igoshev\Captcha\Captcha\Captcha;

class IgoshevCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/igoshev/captcha.php', 'igoshev.captcha');
        $this->loadViewsFrom(__DIR__ . '/../resources/views/vendor/igoshev/captcha', 'igoshev');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang/vendor/igoshev', 'igoshev');

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
        $this->app->singleton('igoshev_captcha', function (Application $app) {
            $config = $app['config']['igoshev']['captcha'];

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

        Blade::directive(config('igoshev.captcha.blade'), function () {
            return "<?php echo Igoshev\\Captcha\\Facades\\Captcha::getView() ?>";
        });
    }

    /**
     * Register captcha routes.
     */
    protected function registerRoutes()
    {
        $this->app['router']->group([
            'middleware' => config('igoshev.captcha.middleware', 'web'),
            'namespace'  => 'Igoshev\Captcha\Controllers',
            'as'         => 'igoshev.captcha.'
        ], function ($router) {
            $router->get(config('igoshev.captcha.routes.image'), 'CaptchaController@image')->name('image');
            $router->get(config('igoshev.captcha.routes.image_tag'), 'CaptchaController@imageTag')->name('image.tag');
        });
    }

    /**
     * Register captcha validator.
     */
    protected function registerValidator()
    {
        Validator::extend(config('igoshev.captcha.validator'), function ($attribute, $value, $parameters, $validator) {
            return $this->app['igoshev_captcha']->validate($value);
        }, trans('igoshev::captcha.incorrect_code'));
    }
}
