<?php

namespace App\EventSubscriber;

use App\Response\DataResponse;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    private DataResponse $response;

    public function __construct(DataResponse $dataResponse){
        $this->response = $dataResponse;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpException) {
            $response = new JsonResponse($this->response->error(DataResponse::STATUS_ERROR, $exception->getMessage()));;
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else return;

        $event->setResponse($response);
    }

    #[ArrayShape(['kernel.exception' => 'string'])]
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
