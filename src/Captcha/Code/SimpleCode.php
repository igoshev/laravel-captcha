<?php

namespace Igoshev\Captcha\Captcha\Code;

class SimpleCode implements CodeInterface
{
    /**
     * @inheritdoc
     */
    public function generate($chars, $minLength, $maxLength)
    {
        $length = mt_rand($minLength, $maxLength);
        $code   = [];
        for ($i = 0; $i < $length; $i++) {
            $code[] = $chars[mt_rand(1, mb_strlen($chars) - 1)];
        }
        $code = implode($code);

        return $code;
    }
}
