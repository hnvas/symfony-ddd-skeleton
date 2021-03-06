<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\EventListeners;

use App\Core\Application\Exceptions\NotFoundException;
use App\Core\Application\Exceptions\InvalidDataException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleHttpException', 10],
                ['handleNotFoundException', 8],
                ['handleInvalidDataException', 6],
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

    public function handleNotFoundException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof NotFoundException) {
            $event->setResponse(new JsonResponse([
                'message' => $throwable->getMessage()
            ], Response::HTTP_NOT_FOUND));
        }
    }

    public function handleInvalidDataException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof InvalidDataException) {
            $event->setResponse(new JsonResponse([
                'message' => $throwable->getMessage(),
                'details' => $throwable->getDetails()
            ], Response::HTTP_UNPROCESSABLE_ENTITY));
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
