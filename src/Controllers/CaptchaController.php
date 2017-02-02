<?php

namespace LaravelCaptcha\Controllers;

use App\Http\Controllers\Controller;
use LaravelCaptcha\Facades\Captcha;

class CaptchaController extends Controller
{
    /**
     * Get image.
     *
     * @return mixed
     */
	public function index()
	{
	    $image = Captcha::getImage();

        return response($image)->header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT')
                               ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
                               ->header('Cache-Control', 'post-check=0, pre-check=0', false)
                               ->header('Pragma', 'no-cache')
                               ->header('Content-Type', 'image/png');
	}

    /**
     * Get html <img> tag.
     *
     * @return mixed
     */
	public function html()
	{
		return response(Captcha::html());
	}
}
