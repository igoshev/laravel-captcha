<?php

Route::group([
    'middleware' => \App::version() >= 5.2 ? 'web' : null,
    'namespace' => 'LaravelCaptcha\Controllers'
], function () {
    Route::get('bonecms_captcha', ['as' => 'laravel-captcha', 'uses' => 'CaptchaController@index']);
    Route::get('bonecms_captcha/html', 'CaptchaController@html');
});
