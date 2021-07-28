<?php

namespace App\Core\Infrastructure\EventListeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleNotFoundHttpException', 1],
                ['handleUnauthorizedHttpException', 0],
                ['handleGenericException', -1]
            ]
        ];
    }

    public function handleUnauthorizedHttpException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        if (
            $throwable instanceof UnauthorizedHttpException ||
            ($throwable instanceof HttpException &&
                $throwable->getStatusCode() == Response::HTTP_UNAUTHORIZED)
        ) {
            $event->setResponse(new JsonResponse([
                'message' => $event->getThrowable()->getMessage()
            ], Response::HTTP_UNAUTHORIZED));
        }
    }

    public function handleNotFoundHttpException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {
            $event->setResponse(new JsonResponse([
                'message' => 'Resource not found'
            ], Response::HTTP_NOT_FOUND));
        }
    }

    public function handleGenericException(ExceptionEvent $event)
    {
        $event->setResponse(new JsonResponse([
            'message'      => "Some error has occurred",
            'traceMessage' => $event->getThrowable()->getMessage()
        ]));
    }
}
