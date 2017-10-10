<?php

namespace Igoshev\Captcha\Captcha\Code;

interface CodeInterface
{
    /**
     * Generate captcha code.
     *
     * @param string $chars Displayed chars.
     * @param int $minLength Min code length.
     * @param int $maxLength Max code length.
     * @return mixed
     */
    public function generate($chars, $minLength, $maxLength);
}
