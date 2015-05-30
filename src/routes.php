<?php
Route::get('bonecms_captcha', ['as' => 'laravel-captcha', 'uses' => 'LaravelCaptcha\Controllers\CaptchaController@index']);
Route::get('bonecms_captcha/html', 'LaravelCaptcha\Controllers\CaptchaController@html');