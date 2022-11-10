<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\EventListeners;

use App\Core\Application\Exceptions\ApplicationException;
use App\Core\Application\Exceptions\ResourceNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionListener
 * @package App\Core\Infrastructure\EventListeners
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class ExceptionListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleResourceNotFoundException', 8],
                ['handleApplicationException', 6],
                ['handleGenericException', -1],
                ['handleHttpException', -3]
            ]
        ];
    }

    public function handleResourceNotFoundException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof ResourceNotFoundException) {
            $event->setResponse(new JsonResponse([
                'message' => $throwable->getMessage()
            ], Response::HTTP_NOT_FOUND));
        }
    }

    public function handleApplicationException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof ApplicationException) {
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

    public function handleHttpException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof HttpException) {
            $event->setResponse(new JsonResponse([
                'message' => $throwable->getMessage()
            ], $throwable->getStatusCode()));
        }
    }
}
