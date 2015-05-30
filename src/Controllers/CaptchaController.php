<?php namespace LaravelCaptcha\Controllers;

use App\Http\Controllers\Controller;
use LaravelCaptcha\Lib\Captcha;

class CaptchaController extends Controller {

	public function index(Captcha $captcha)
	{
		return $captcha->get();
	}

	public function html(Captcha $captcha)
	{
		return $captcha->html();
	}

}
