<?php

declare(strict_types=1);

namespace App\Shared\Application\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidationException extends HttpException
{
    private array $errors;

    public function __construct(
        array $errors,
        int $statusCode = Response::HTTP_BAD_REQUEST,
        ?\Throwable $previous = null,
        array $headers = [],
        ?int $code = 0,
    ) {
        parent::__construct($statusCode, 'Validation Error', $previous, $headers, $code);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
