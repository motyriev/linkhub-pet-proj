<?php

declare(strict_types=1);

namespace App\Api\Controller\User;

use App\Api\Controller\AbstractController;
use App\Api\Request\User\CreateUserRequestDTO;
use App\Shared\Application\Enum\Status;
use App\Shared\Domain\Security\UserFetcherInterface;
use App\User\Application\UseCase\UserInteractor;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserFetcherInterface $userFetcher,
        private readonly UserInteractor $userInteractor,
    ) {
    }

    #[OA\Post(
        path: '/api/users/register',
        description: 'Creates a new user with the specified email, username, and password',
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'username', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'username', type: 'string', example: 'user123'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                ]
            ),
        ),
        tags: ['User'],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'User registered successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'User registered successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid data or user already exists',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Email is already taken'),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_INTERNAL_SERVER_ERROR,
                description: 'Server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'An internal server error occurred'
                        ),
                    ]
                )
            ),
        ]
    )]
    #[Route('/api/users/register', methods: ['POST'])]
    public function register(CreateUserRequestDTO $request): JsonResponse
    {
        $this->userInteractor->registerWithPage($request->email, $request->username, $request->password);

        return new JsonResponse(['message' => 'User registered successful', 'status' => Status::SUCCESS],
            Response::HTTP_CREATED);
    }

    #[OA\Get(
        path: '/api/users/me',
        description: 'Returns the information of the authenticated user',
        summary: 'Get current user information',
        security: [
            ['bearerAuth' => []],
        ],
        tags: ['User'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'User information retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'username', type: 'string', example: 'user123'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'User is not authenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'code', type: 'integer', example: '401'),
                        new OA\Property(property: 'message', type: 'string', example: 'JWT Token not found'),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_INTERNAL_SERVER_ERROR,
                description: 'Server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'An internal server error occurred'
                        ),
                    ]
                )
            ),
        ]
    )]
    #[Route('/api/users/me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->userFetcher->getAuthUser();

        return new JsonResponse(
            ['status' => Status::SUCCESS, 'data' => ['username' => $user->getUsername()]],
            Response::HTTP_OK
        );
    }
}
