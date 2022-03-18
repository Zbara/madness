<?php

namespace App\Service;

use App\Entity\Session;
use App\Model\UserParams;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;

class Auth
{
    var array $app = [
        'appId' => 3945528,
        'secretKey' => 'S3kKi8TC9Y'
    ];

    var array $data = ['platform_id', 'auth_key', 'version', 'language'];
    var array $required = ['session_id', 'session_key'];


    private UserParams $params;
    private Routing $routing;
    private SessionRepository $sessionRepository;
    private EntityManagerInterface $manager;

    public function __construct(UserParams $params, Routing $routing, SessionRepository $sessionRepository, EntityManagerInterface $manager)
    {
        $this->params = $params;
        $this->routing = $routing;
        $this->sessionRepository = $sessionRepository;
        $this->manager = $manager;
    }

    public function helper(string $params, string $route = null)
    {
        $params = $this->decode($params);

        if (!is_array($params)) {
            return $params;
        } elseif ($data = $this->validate($params)) {
            return $data;
        }
        return $this->examination($params, $route);
    }


    private function examination(array $params, string $route): array|bool
    {
        $this->setter($params);

        if (!$this->sig($params)) {
            return ['Wrong signature or auth error.'];
        } elseif ($this->getSession($params, $route)) {
            return ['Wrong user session'];
        }
        return true;
    }

    private function getSession(array $params, string $route): bool
    {
        if (!in_array($route, ['app_init', 'app_authorize'])) {
            $session = $this->sessionRepository->findOneBy(['session_key' => $this->params->getSessionKey(), 'count' => $this->params->getSessionId()]);

            if (isset($session)) {
                return false;
            } else return true;
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
                if (in_array($key,['app_friends', 'methods'])) {
                    $this->params->$key = explode(',', $item);
                } else $this->params->$key = $item;
            }
        }
    }

    private function validate(array $params): array
    {
        $error = [];

        $this->data = array_merge($this->routing->getRuterOptions(), $this->data);

        if (!in_array($this->routing->getRoute(), ['app_init', 'app_authorize'])) {
            $this->data = array_merge($this->required, $this->data);
        }

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
