<?php

namespace Igoshev\Captcha\Captcha\Storage;

use Illuminate\Http\Request;

class SessionStorage implements StorageInterface
{
    /**
     * @var \Illuminate\Session\Store
     */
    private $session;

    /**
     * @var string
     */
    private $key = 'bone_captcha';

    /**
     * SessionStorage constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->session = $request->session();
    }

    /**
     * @inheritdoc
     */
    public function push($code)
    {
        $this->session->put($this->key, $code);
    }

    /**
     * @inheritdoc
     */
    public function pull()
    {
        $code = $this->session->get($this->key);
        if (! empty($code)) {
            $this->session->forget($this->key);
        }

        return $code;
    }
}
