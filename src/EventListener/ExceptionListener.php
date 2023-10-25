<?php

namespace App\EventListener;

use App\Exception\PublicException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

#[AsEventListener]
final class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse();

        if ($exception instanceof PublicException) {
            $response
                ->setStatusCode(Response::HTTP_BAD_REQUEST)
                ->setData([
                    'err_message' => $exception->getMessage(),
                ]);
        } elseif ($exception instanceof HttpExceptionInterface) {
            $response
                ->setStatusCode($exception->getStatusCode())
                ->setData([
                    'err_message' => $exception->getMessage(),
                ])
                ->headers
                ->replace($exception->getHeaders());

        } else {
            $response
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setData([
//                    'err_message' => 'something went wrong'
                    'err_message' => $exception->getMessage()
                ]);
        }

        $event->setResponse($response);
    }
}