<?php

namespace Igoshev\Captcha\Captcha\Storage;

interface StorageInterface
{
    /**
     * Push the code in the storage.
     *
     * @param string $code
     * @return void
     */
    public function push($code);

    /**
     * Pull the code from the storage.
     *
     * @return string|null
     */
    public function pull();
}
