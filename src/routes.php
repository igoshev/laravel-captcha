<?php
Route::get('bonecms_captcha', 'LaravelCaptcha\Controllers\CaptchaController@index');
Route::get('bonecms_captcha/get_css','LaravelCaptcha\Controllers\CaptchaController@getCSS');