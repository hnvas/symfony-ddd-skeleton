<?php

namespace App\Core\Infrastructure\EventListeners;

use App\Core\Application\Exceptions\EntityNotFoundException;
use App\Core\Application\Exceptions\InvalidEntityException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleHttpException', 10],
                ['handleEntityNotFoundException', 8],
                ['handleInvalidEntityException', 6],
                ['handleGenericException', -1]
            ]
        ];
    }

    public function handleHttpException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof HttpException) {
            $event->setResponse(new JsonResponse([
                'message' => $throwable->getMessage()
            ], $throwable->getStatusCode()));
        }
    }

    public function handleEntityNotFoundException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof EntityNotFoundException) {
            $event->setResponse(new JsonResponse([
                'message' => $throwable->getMessage()
            ], Response::HTTP_NOT_FOUND));
        }
    }

    public function handleInvalidEntityException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof InvalidEntityException) {
            $event->setResponse(new JsonResponse([
                'message' => $throwable->getMessage(),
                'details' => $throwable->getDetails()
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
