<?php

namespace App\Service;

use App\Entity\Session;
use App\Exception\ParamsException;
use App\Exception\SessionException;
use App\Exception\SignatureException;
use App\Exception\ValidateException;
use App\Model\UserParams;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionProperty;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthService
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
    private ValidatorInterface $validator;

    public function __construct(
        UserParams             $params,
        Routing                $routing,
        SessionRepository      $sessionRepository,
        ValidatorInterface $validator,

    )
    {
        $this->params = $params;
        $this->routing = $routing;
        $this->sessionRepository = $sessionRepository;
        $this->validator = $validator;
    }

    public function helper(string $params): void
    {
        $params = $this->decode($params);

        if (is_array($params)) {
            $this->validate($params)->examination($params);
        } else throw new ParamsException($params);
    }

    private function examination(array $params): void
    {
        $this->setter($params);

        if (empty($this->sig($params))) {
            throw new SignatureException('Wrong signature.');
        }
        $this->getSession($params);
    }

    private function getSession(array $params): void
    {
        if (!in_array($this->routing->getRoute(), ['app_init', 'app_authorize'])) {
            $session = $this->sessionRepository->findOneBy(['session_key' => $this->params->getSessionKey(), 'count' => $this->params->getSessionId()]);

            if (isset($session)) {
                $this->params->setUser($session->getUser());
            } else throw new SessionException('Wrong user session');
        }
    }

    private function sig(array $params): bool
    {
        $auth_key = md5($this->app['appId'] . '_' . $params['platform_id'] . '_' . $this->app['secretKey']);

        return $auth_key == $params['auth_key'];
    }

    private function setter(array $params): void
    {
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
                    throw new ParamsException($e->getMessage());
                }
            }
        }
    }

    private function validate(array $params): AuthService
    {
        $this->data = array_merge($this->routing->getRuterOptions(), $this->data);

        if (!in_array($this->routing->getRoute(), ['app_init', 'app_authorize'])) {
            $this->data = array_merge($this->required, $this->data);
        }
        $extra = array_diff(array_keys($params), $this->data);

        if (count($extra) === 0) {
            foreach ($this->data as $key => $item) {
                if (empty(array_key_exists($item, $params))) {
                    $error = sprintf('Param [%s] required', $item);
                }
            }
        } else $error = sprintf('Extra data, [%s]', implode(', ', $extra));

        if (isset($error)) {
            throw new ValidateException($error);
        }
        return $this;
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
