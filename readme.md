# Captcha integration for the Laravel 5
##Installing Laravel Captcha Composer Package
Note: If you do not have Composer yet, you can install it by following the instructions on https://getcomposer.org
####Step 1. Install package
```bash
composer require bonecms/laravel-captcha
```
####Step 2. Register the Laravel Captcha service provider
{LARAVEL_ROOT}/config/app.php:
```php
'providers' => [
    ...
    LaravelCaptcha\Providers\LaravelCaptchaServiceProvider::class
],
```
####Step 3. Only 5.2 and more. 
It must be specified middleware "web" where the captcha validation.
Since version 5.3 routes contains middleware "web" already. It defined by the provider "App\ProvidersRouteServiceProvider".

##Using Laravel Captcha
Generate a Captcha markup in your Controller:
```php
<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelCaptcha\Facades\Captcha;

class MyController extends Controller 
{
    public function getExample() 
    {
        return view('myView', ['captcha' => Captcha::html()]);
    }

}
```
Showing a Captcha in a View:
```html
...
{!! $captcha !!}
<input type="text" id="captcha" name="captcha">
 ...
```
Check user input during form submission:
```php
<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelCaptcha\Facades\Captcha;

class MyController extends Controller 
{
    public function getExample() 
    {
        return view('myView', ['captcha' => Captcha::html()]);
    }

    public function postExample(Request $request)
    {
    	$this->validate($request, [
            'captcha' => 'required|bone_captcha'
        ]);

	    // Validation passed
    }
}
```
##Captcha configuration
####Create captcha.php file and add the following
{LARAVEL_ROOT}/config/captcha.php:
```php
<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Font
	|--------------------------------------------------------------------------
	| Supported: "DroidSerif".
	|
	*/
	'font' => 'DroidSerif',

	/*
	|--------------------------------------------------------------------------
	| Font size
	|--------------------------------------------------------------------------
	| Font size in pixels.
	| 
	|
	*/
	'fontSize' => 26,

	/*
	|--------------------------------------------------------------------------
	| Letter spacing
	|--------------------------------------------------------------------------
	| Spacing between letters in pixels.
	|
	*/
	'letterSpacing' => 2,

	/*
	|--------------------------------------------------------------------------
	| Code Length
	|--------------------------------------------------------------------------
	| You can specify an array or integer.
	|
	*/
	'length' => [4, 5],

	/*
	|--------------------------------------------------------------------------
	| Displayed chars
	|--------------------------------------------------------------------------
	| Enter the different characters.
	|
	*/
	'chars' => 'QSFHTRPAJKLMZXCVBNabdefhxktyzj23456789',

	/*
	|--------------------------------------------------------------------------
	| Image Size
	|--------------------------------------------------------------------------
	| Captcha image size can be controlled by setting the width 
	| and height properties.
	| 
	|
	*/
	'width' => 180,
	'height' => 50,

	/*
	|--------------------------------------------------------------------------
	| Background Captcha
	|--------------------------------------------------------------------------
	| You can specify an array or string.
	|
	*/
	'background' => 'f2f2f2',

	/*
	|--------------------------------------------------------------------------
	| Colors characters
	|--------------------------------------------------------------------------
	| You can specify an array or string.
	|
	*/
	'colors' => '2980b9',

	/*
	|--------------------------------------------------------------------------
	| Scratches
	|--------------------------------------------------------------------------
	| The number of scratches displayed in the Captcha.
	|
	*/
	'scratches' => 30,

	/*
	|--------------------------------------------------------------------------
	| Captcha style
	|--------------------------------------------------------------------------
	| Supported: "wave".
	|
	*/
	'style' => 'wave',

	/*
	|--------------------------------------------------------------------------
	| Id of the Captcha code input textbox
	|--------------------------------------------------------------------------
	| After updating the Captcha focus will be set on an element with this id.
	|
	*/
	'inputId' => 'captcha',

];
```