<?php namespace LaravelCaptcha\Lib;

use Config;
use Session;
use Response;

class Captcha {

	/**
	 * Captcha parameters.
	 * @var array.
	 */	
	private $params = [];

	/**
	 * Style classes Captcha.
	 * @var array.
	 */
	private $styles = [
		'wave' => 'LaravelCaptcha\Lib\CaptchaWaves',
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

		if (!is_null(Config::get('captcha'))) {
			$this->params = array_merge($defaultParams, Config::get('captcha'));
		}
		else {
			$this->params = $defaultParams;
		}

		$this->params['font'] = __DIR__ . '/../Resourses/Fonts/'. $this->params['font'] .'/'. $this->params['font'] .'.ttf';
		$this->params['background'] = is_array($this->params['background']) ? $this->params['background'] : [$this->params['background']];
		$this->params['colors'] = is_array($this->params['colors']) ? $this->params['colors'] : [$this->params['colors']];
	}

	/**
	 * Output a PNG image.
	 * @return image/png.
	 */
	public function get()
	{
		$length = is_array($this->params['length']) ? mt_rand($this->params['length'][0], $this->params['length'][1]) : $this->params['length'];
		$str = '';
		for ($i = 0; $i < $length; $i++) {
		    $str .= $this->params['chars'][mt_rand(1, strlen($this->params['chars']) - 1)];
		}
		
		Session::flash('captcha', $str);

		$generator = $this->styles[$this->params['style']];
		$content = (new $generator())->render($str, $this->params);

		return Response::make($content)->header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT')
									   ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
									   ->header('Cache-Control', 'post-check=0, pre-check=0', false)
									   ->header('Pragma', 'no-cache')
									   ->header('Content-Type', 'image/png');
	}

	/**
	 * Captcha validation.
	 * @param string $code Code.
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 */
	public function validate($code)
	{
		return (is_string($code) && Session::get('captcha') === $code);
	}

	/**
	 * Get html image code.
	 * @return string Html image code. 
	 */
	public function html()
	{
		return '<img src="'. route('laravel-captcha') .'" alt="https://github.com/bonecms/laravel-captcha" style="cursor:pointer;width:'. $this->params['width'] .'px;height:'. $this->params['height'] .'px;" title="Update" onclick="this.setAttribute(\'src\',\''. route('laravel-captcha') .'?_=\'+Math.random());var captcha=document.getElementById(\''.$this->params['inputId'].'\');if(captcha){captcha.focus()}">';
	}
}