<?php namespace LaravelCaptcha\Lib;

interface CaptchaInterface {

	/**
	 * Rendering captcha.
	 * @param string $str Letters for the captcha.
	 * @param array $params Parameters rendering.
	 * @return string PNG image.
	 */
	public function render($str, $params);

}