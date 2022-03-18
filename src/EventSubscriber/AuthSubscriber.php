<?php

namespace App\EventSubscriber;

use App\Response\DataResponse;
use App\Service\Auth;
use App\Service\Routing;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;


class AuthSubscriber implements EventSubscriberInterface
{
    private DataResponse $response;
    private Auth $auth;
    private Routing $routing;

    /**
     * @param DataResponse $dataResponse
     * @param Auth $auth
     * @param Routing $routing
     */
    public function __construct(
        DataResponse $dataResponse,
        Auth         $auth,
        Routing      $routing
    )
    {
        $this->response = $dataResponse;
        $this->auth = $auth;
        $this->routing = $routing;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            if ($params = $event->getRequest()->query->get('params')) {
                $this->routing->setRouteName($event->getRequest()->attributes->get('_route'));

                $auth = $this->auth->helper($params, $event->getRequest()->attributes->get('_route'));

                if (is_array($auth) || is_string($auth)) {
                    $response = new JsonResponse($this->response->error(DataResponse::STATUS_ERROR, $auth));;
                    $event->setResponse($response);
                }
                return;
            }
            $response = new JsonResponse($this->response->error(DataResponse::STATUS_ERROR, 'Param [params] required'));;
            $event->setResponse($response);
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
