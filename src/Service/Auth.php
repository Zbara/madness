<?php

namespace App\Service;

use App\Model\UserParams;

class Auth
{
    var array $app = [
        'appId' => 3945528,
        'secretKey' => 'S3kKi8TC9Y'
    ];

    var array $data = ['platform_id', 'auth_key', 'version', 'language'];

    private UserParams $params;
    private Routing $routing;

    public function __construct(UserParams $params, Routing $routing)
    {
        $this->params = $params;
        $this->routing = $routing;
    }

    public function helper(string $params, string $route = null)
    {
        $params = $this->decode($params);

        if (!is_array($params)) {
            return $params;
        } elseif ($data = $this->validate($params)) {
            return $data;
        } elseif (!$this->sig($params)) {
            return ['Wrong signature'];
        } elseif ($this->getSession($params, $route)) {
            return ['Wrong user session'];
        }
        $this->setter($params);
    }

    private function getSession(array $params, string $route): bool
    {
        if (!in_array($route, ['app_init', 'app_authorize'])) {
            return true;
        } else return false;
    }

    private function sig(array $params): bool
    {
        $auth_key = md5($this->app['appId'] . '_' . $params['platform_id'] . '_' . $this->app['secretKey']);

        return $auth_key == $params['auth_key'];
    }

    private function setter(array $params)
    {
        foreach ($params as $key => $item) {
            if (!in_array($key, ['auth_key', 'version', 'language'])) {
                if($key === 'app_friends'){
                    $this->params->$key = explode(',', $item);
                } else $this->params->$key = $item;
            }
        }
    }

    private function validate(array $params): array
    {
        /** Добавление в массив обязательных полей для этого роута */
        $this->data = array_merge($this->routing->getRuterOptions(), $this->data);

        $error = [];

        foreach ($this->data as $key => $item) {
            if (empty(array_key_exists($item, $params))) {
                $error[] = sprintf('Param [%s] required', $item);
            }
        }
        return $error;
    }

    private function decode(string $params)
    {
        try {
            return json_decode(json: $params, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $e->getMessage() . ' JSON.';
        }
    }
}
