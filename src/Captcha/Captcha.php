<?php

namespace Igoshev\Captcha\Captcha;

use Igoshev\Captcha\Captcha\Storage\StorageInterface;
use Igoshev\Captcha\Captcha\Generator\GeneratorInterface;
use Igoshev\Captcha\Captcha\Code\CodeInterface;

class Captcha
{
    /**
     * @var CodeInterface
     */
    private $code;

    /**
     * @var StorageInterface
     */
    private $storage;

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
     * Captcha constructor.
     *
     * @param CodeInterface $code
     * @param StorageInterface $storage
     * @param GeneratorInterface $generator
     * @param array $params
     */
    public function __construct(
        CodeInterface $code,
        StorageInterface $storage,
        GeneratorInterface $generator,
        array $params
    ) {
        $this->code      = $code;
        $this->storage   = $storage;
        $this->generator = $generator;
        $this->params    = $params;

        $this->params['background'] = is_array($this->params['background']) ? $this->params['background'] : [$this->params['background']];
        $this->params['colors']     = is_array($this->params['colors']) ? $this->params['colors'] : [$this->params['colors']];
    }

    /**
     * Output a PNG image.
     *
     * @return mixed
     */
    public function getImage()
    {
        $code = $this->code->generate(
            $this->params['chars'],
            $this->params['length'][0],
            $this->params['length'][1]
        );

        $this->storage->push($code);

        return $this->generator->render($code, $this->params);
    }

    /**
     * Captcha validation.
     *
     * @param string $code Code.
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function validate($code)
    {
        $correctCode = $this->storage->pull();

        if (! empty($correctCode)) {
            return mb_strtolower($correctCode) === mb_strtolower($code);
        }

        return false;
    }

    /**
     * Get html image tag.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getView()
    {
        return view('igoshev::captcha.image', [
            'route' => route('igoshev.captcha.image') . '?' . mt_rand(),
            'title' => trans('igoshev::captcha.update_code'),
            'width' => config('igoshev.captcha.width'),
            'height' => config('igoshev.captcha.height'),
            'input_id' => config('igoshev.captcha.inputId'),
        ]);
    }
}
