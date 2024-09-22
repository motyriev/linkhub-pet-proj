<?php

declare(strict_types=1);

namespace App\Api\Request;

use App\Shared\Application\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_subclass_of($argument->getType(), BaseRequest::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$this->supports($request, $argument)) {
            return [];
        }

        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                $argument->getType(),
                'json'
            );
        } catch (NotEncodableValueException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid JSON format: '.$e->getMessage());
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $outputErrors = [];

            foreach ($errors as $error) {
                $outputErrors[] = [
                    'field' => $error->getPropertyPath(),
                    'message' => $error->getMessage(),
                ];
            }

            throw new ValidationException($outputErrors);
        }

        yield $dto;
    }
}
