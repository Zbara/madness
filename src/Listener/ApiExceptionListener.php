<?php

namespace App\Listener;

use App\Response\DataResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionListener
{
    private DataResponse $dataResponse;

    public function __construct(
        DataResponse $dataResponse
    )
    {
        $this->dataResponse = $dataResponse;
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $event->setResponse(
            new JsonResponse(
                $this->dataResponse->error(
                    $throwable->getCode(),
                    $throwable->getMessage(),
                    get_class($throwable),
                    $throwable->getTraceAsString()
                )
            )
        );
    }
}

