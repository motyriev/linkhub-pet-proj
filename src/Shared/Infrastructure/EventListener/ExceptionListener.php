<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventListener;

use App\Shared\Application\Enum\Status;
use App\Shared\Application\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Webmozart\Assert\InvalidArgumentException;

class ExceptionListener
{
    private const MIME_JSON = 'application/json';

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly string $appEnv,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $this->logger->error($exception->getMessage(), ['exception' => $exception]);

        //        if ($this->appEnv === 'dev' || $this->appEnv === 'test') {
        //            return;
        //        }

        //        if (php_sapi_name() === 'cli') {
        //            $message = sprintf('Error: %s', $exception->getMessage());
        //            echo $message . PHP_EOL;
        //            return;
        //        }
        $acceptHeader = $event->getRequest()->headers->get('Accept');
        if (self::MIME_JSON === $acceptHeader) {
            $exception = $event->getThrowable();
            $response = new JsonResponse();
            $response->setContent($this->exceptionToJson($exception));
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } elseif ($exception instanceof HandlerFailedException) {
                $originalException = $exception->getPrevious();
                if ($originalException instanceof InvalidArgumentException) {
                    $response->setContent($this->exceptionToJson($originalException));
                }
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $event->setResponse($response);
        }
    }

    public function exceptionToJson(\Throwable $exception): string
    {
        if ($exception instanceof ValidationException) {
            return json_encode(
                [
                    'status' => Status::ERROR,
                    'message' => $exception->getMessage(),
                    'errors' => $exception->getErrors(),
                ]
            );
        }

        return json_encode(
            [
                'status' => Status::ERROR,
                'message' => $exception->getMessage(),
            ]
        );
    }
}
