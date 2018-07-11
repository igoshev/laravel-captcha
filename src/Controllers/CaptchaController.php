<?php

namespace Igoshev\Captcha\Controllers;

use Illuminate\Routing\Controller as Controller;
use Igoshev\Captcha\Facades\Captcha;

class CaptchaController extends Controller
{
    /**
     * Get image.
     *
     * @return mixed
     */
    public function image()
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function imageTag()
    {
        return Captcha::getView();
    }
}
