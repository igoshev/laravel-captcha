<?php

namespace LaravelCaptcha\Captcha;

use Illuminate\Support\Facades\Session;
use LaravelCaptcha\Captcha\Generators\GeneratorInterface;

class Captcha
{
    /**
     * @var GeneratorInterface
     */
    private $generator;

	/**
	 * Captcha parameters.
     *
	 * @var array.
	 */	
	private $params = [];

	/**
	 * Style classes Captcha.
     *
	 * @var array.
	 */
	private $styles = [
		'wave' => 'LaravelCaptcha\Captcha\Generators\GeneratorWaves',
	];

	public function __construct()
	{
		$defaultParams = [
			'font' => 'DroidSerif', //Font
			'fontSize' => 26, //Font size
			'letterSpacing' => 2, //Letter spacing
			'length' => [4, 5], //Code Length
			'chars' => 'QSFHTRPAJKLMZXCVBNabdefhxktyzj23456789', //Displayed symbols
			'width' => 180, //Image Size
			'height' => 50, //Image Size
			'background' => 'f2f2f2', //The background Captcha
			'colors' => ['27ae60','2980b9','8e44ad','2c3e50'], //Colors characters
			'scratches' => 30, //The number of scratches displayed in the Captcha
			'style' => 'wave', //Captcha style
			'inputId' => 'captcha', //Id of the Captcha code input textbox
		];

		$params = config('captcha');

        $this->params = !is_null($params) ? array_merge($defaultParams, $params) : $defaultParams;

		$this->params['font'] = __DIR__ . '/../resources/fonts/'. $this->params['font'] .'/'. $this->params['font'] .'.ttf';
		$this->params['background'] = is_array($this->params['background']) ? $this->params['background'] : [$this->params['background']];
		$this->params['colors'] = is_array($this->params['colors']) ? $this->params['colors'] : [$this->params['colors']];

        $generator = $this->styles[$this->params['style']];
        $this->generator = new $generator();
	}

    /**
     * Generate captcha code.
     *
     * @return string
     */
    private function generateCode()
    {
        $length = is_array($this->params['length']) ? mt_rand($this->params['length'][0], $this->params['length'][1]) : $this->params['length'];
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $this->params['chars'][mt_rand(1, strlen($this->params['chars']) - 1)];
        }

        session(['bone_captcha' => $code]);

        return $code;
    }

    /**
     * Output a PNG image.
     *
     * @return mixed
     */
	public function getImage()
	{
		return $this->generator->render($this->generateCode(), $this->params);
	}

	/**
	 * Captcha validation.
     *
	 * @param string $code Code.
	 * @return bool Returns TRUE on success or FALSE on failure.
	 */
	public function validate($code)
	{
        $bone_captcha = session('bone_captcha');

        Session::forget('bone_captcha');

	    if (!empty($bone_captcha)) {
            return strtolower($bone_captcha) === strtolower($code);
        }

        return false;
	}

    /**
     * Get html image code.
     *
     * @return string Html image code.
     */
	public function html()
	{
		return '<img src="'. route('laravel-captcha') .'" alt="https://github.com/bonecms/laravel-captcha" style="cursor:pointer;width:'. $this->params['width'] .'px;height:'. $this->params['height'] .'px;" title="'.trans('bone_captcha::trans.update_code').'" onclick="this.setAttribute(\'src\',\''. route('laravel-captcha') .'?_=\'+Math.random());var captcha=document.getElementById(\''.$this->params['inputId'].'\');if(captcha){captcha.focus()}">';
	}
}
