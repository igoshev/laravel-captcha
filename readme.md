# Captcha integration for the Laravel 5
Package information:

[![Latest Stable Version](https://poser.pugx.org/bonecms/laravel-captcha/v/stable)](https://packagist.org/packages/bonecms/laravel-captcha)
[![Total Downloads](https://poser.pugx.org/bonecms/laravel-captcha/downloads)](https://packagist.org/packages/bonecms/laravel-captcha)
[![License](https://poser.pugx.org/bonecms/laravel-captcha/license)](https://packagist.org/packages/bonecms/laravel-captcha)

For Laravel 5.3 and below:

[Version 1.1](https://github.com/igoshev/laravel-captcha/releases/tag/v1.1)

## Installing Laravel Captcha Composer Package
Note: If you do not have Composer yet, you can install it by following the instructions on https://getcomposer.org
#### Step 1. Install package
```bash
composer require bonecms/laravel-captcha
```
#### Step 2. Register the Laravel Captcha service provider
{LARAVEL_ROOT}/config/app.php:
```php
'providers' => [
    ...
    Igoshev\Captcha\Providers\IgoshevCaptchaServiceProvider::class,
],
```
#### Step 3. 
It must be specified middleware "web" where the captcha validation.
Since version 5.3 routes contains middleware "web" already. It defined by the provider "App\ProvidersRouteServiceProvider".

## Using Laravel Captcha
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
        return view('myView');
    }

}
```
Showing a Captcha in a View:
```html
...
@captcha
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
        return view('myView');
    }

    public function postExample(Request $request)
    {
    	$this->validate($request, [
            'captcha' => 'required|captcha'
        ]);

	    // Validation passed
    }
}
```
### Captcha configuration
```bash
php artisan vendor:publish --provider="Igoshev\Captcha\Providers\IgoshevCaptchaServiceProvider" --tag="config"
```
```php
<?php

return [

/*
    |--------------------------------------------------------------------------
    | Captcha middleware
    |--------------------------------------------------------------------------
    |
    */
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Captcha routes
    |--------------------------------------------------------------------------
    |
    */
    'routes' => [
        'image'     => 'igoshev/captcha/image',
        'image_tag' => 'igoshev/captcha/image_tag'
    ],

    /*
    |--------------------------------------------------------------------------
    | Blade directive
    |--------------------------------------------------------------------------
    | You can use blade directive @captcha for rendering captcha.
    |
    */
    'blade' => 'captcha',

    /*
    |--------------------------------------------------------------------------
    | Validator name
    |--------------------------------------------------------------------------
    |
    */
    'validator' => 'captcha',

    /*
    |--------------------------------------------------------------------------
    | Captcha generator.
    |--------------------------------------------------------------------------
    | Must implement GeneratorInterface.
    |
    */
    'generator' => \Igoshev\Captcha\Captcha\Generator\GeneratorWaves::class,

    /*
    |--------------------------------------------------------------------------
    | Storage code.
    |--------------------------------------------------------------------------
    | Must implement StorageInterface.
    |
    */
    'storage' => \Igoshev\Captcha\Captcha\Storage\SessionStorage::class,

    /*
    |--------------------------------------------------------------------------
    | Code generator.
    |--------------------------------------------------------------------------
    | Must implement CodeInterface.
    |
    */
    'code' => \Igoshev\Captcha\Captcha\Code\SimpleCode::class,

    /*
    |--------------------------------------------------------------------------
    | Font
    |--------------------------------------------------------------------------
    | Supported: "DroidSerif".
    |
    */
    'font' => base_path('vendor/bonecms/laravel-captcha/src/resources/fonts/DroidSerif/DroidSerif.ttf'),

    /*
    |--------------------------------------------------------------------------
    | Font size
    |--------------------------------------------------------------------------
    | Font size in pixels.
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
    'width'  => 180,
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
    'scratches' => [1, 6],

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
### Localization
```bash
php artisan vendor:publish --provider="Igoshev\Captcha\Providers\IgoshevCaptchaServiceProvider" --tag="lang"
```

### View
```bash
php artisan vendor:publish --provider="Igoshev\Captcha\Providers\IgoshevCaptchaServiceProvider" --tag="views"
```
