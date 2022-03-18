<?php

namespace App\Game\User;

use App\Entity\Session;
use App\Entity\Users;
use App\Model\UserParams;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;

class SessionOption
{

    private UserParams $params;
    private EntityManagerInterface $manager;

    public function __construct(
        UserParams             $params,
        EntityManagerInterface $manager,
    )
    {
        $this->params = $params;
        $this->manager = $manager;
    }

    #[ArrayShape([
        'session_id' => "int",
        'session_key' => "string"
    ])]
    public function setSession(?Users $user): array
    {
        $session = $user->getSession();

        if ($session == null) {
            $session = new Session();
            $session->setUser($user);
            $session->setCount(1);
        }
        $session->setCreated(time())
            ->setCount($session->getCount() + 1)
            ->setFriends($this->params->getAppFriends())
            ->setReferrer('vk')
            ->setSessionKey($this->sessionKey($user));

        return [
            'session_id' => $session->getCount(),
            'session_key' => $session->getSessionKey()
        ];
    }

    private function sessionKey(Users $user): string
    {
        $key = $user->getId() . '_' . time();

        foreach ($this->params as $i => $param) {
            if (in_array(gettype($param), ['string', 'integer'])) {
                $key .= $i . '_' . $param;
            }
        }
        return md5($key);
    }
}
