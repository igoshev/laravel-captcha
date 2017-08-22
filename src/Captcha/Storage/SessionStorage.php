<?php

namespace Bone\Captcha\Captcha\Storage;

use Illuminate\Support\Facades\Session;

class SessionStorage implements StorageInterface
{
    protected $key = 'bone_captcha';

    /**
     * @inheritdoc
     */
    public function push($code)
    {
        session([$this->key => $code]);
    }

    /**
     * @inheritdoc
     */
    public function pull()
    {
        $bone_captcha = session($this->key);

        if (!empty($bone_captcha)) {
            Session::forget($this->key);
        }

        return $bone_captcha;
    }
}
