<?php

namespace Igoshev\Captcha\Captcha\Storage;

interface StorageInterface
{
    public function push($code);

    public function pull();
}
