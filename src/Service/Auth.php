<?php

namespace App\Service;

use App\Entity\Session;
use App\Model\UserParams;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionProperty;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    private ValidatorInterface $validator;

    public function __construct(
        UserParams $params,
        Routing $routing,
        SessionRepository $sessionRepository,
        EntityManagerInterface $manager,
        ValidatorInterface $validator,
        RouterInterface $router
    )
    {
        $this->params = $params;
        $this->routing = $routing;
        $this->sessionRepository = $sessionRepository;
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function helper(string $params, string $route = null)
    {
        $this->routing->setRouteName($route);

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
        if ($setter = $this->setter($params)) {
            return $setter;
        }
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
                $this->params->setUser($session->getUser());
                return false;
            } else return true;
        } else return false;
    }

    private function sig(array $params): bool
    {
        $auth_key = md5($this->app['appId'] . '_' . $params['platform_id'] . '_' . $this->app['secretKey']);

        return $auth_key == $params['auth_key'];
    }

    private function setter(array $params): array
    {
        $error = [];

        foreach ($params as $key => $item) {
            if (!in_array($key, ['auth_key', 'version', 'language', 'sig'])) {
                try {
                    $property = new ReflectionProperty($this->params, $key);
                    $property->setAccessible(true);

                    if ($property->getType() == 'array') {
                        $property->setValue($this->params, explode(',', $item));
                    } elseif ($property->getType() == 'string') {
                        $property->setValue($this->params, (string)$item);
                    } elseif ($property->getType() == 'int') {
                        $property->setValue($this->params, (int)$item);
                    }
                } catch (\ReflectionException $e) {
                    $error[] = $e->getMessage();
                }
            }
        }
        return $error;
    }

    private function validate(array $params): array
    {
        $error = [];

        $this->data = array_merge($this->routing->getRuterOptions(), $this->data);

        if (!in_array($this->routing->getRoute(), ['app_init', 'app_authorize'])) {
            $this->data = array_merge($this->required, $this->data);
        }
        $error = array_diff(array_keys($params), $this->data);

        if (count($error) === 0) {
            foreach ($this->data as $key => $item) {
                if (empty(array_key_exists($item, $params))) {
                    $error[] = sprintf('Param [%s] required', $item);
                }
            }
        } else $error = [sprintf('Extra data, [%s].', implode(',', $error))];

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
