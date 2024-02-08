<?php

namespace App\Listener;

use App\Exception\AuthException;
use App\Response\DataResponse;
use App\Service\AuthService;
use App\Service\Routing;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;


class AuthListener implements EventSubscriberInterface
{
    private AuthService $auth;

    /**
     * @param AuthService $auth
     */
    public function __construct(
        AuthService $auth,
    )
    {
        $this->auth = $auth;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            if ($params = $event->getRequest()->query->get('params')) {

                $this->auth->helper($params);

                return;
            }
            throw new AuthException('Param [params] required');
        }
    }

    #[ArrayShape(['kernel.request' => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}
