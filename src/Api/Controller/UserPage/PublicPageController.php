<?php

declare(strict_types=1);

namespace App\Api\Controller\UserPage;

use App\Api\Controller\AbstractController;
use App\Shared\Application\Enum\Status;
use App\UserPage\Application\UseCase\PublicPageInteractor;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicPageController extends AbstractController
{
    public function __construct(
        private readonly PublicPageInteractor $publicPageInteractor,
    ) {
    }

    #[OA\Get(
        path: '/api/page/{username}',
        description: 'Fetches the public page data for a specific user by their username.',
        summary: 'Get public page by username',
        tags: ['Public Page'],
        parameters: [
            new OA\Parameter(
                name: 'username',
                description: 'Username of the page owner',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'user123')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Public page data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(
                                property: 'pageId',
                                description: 'Unique ID of the page (ULID)',
                                type: 'string',
                                example: '01FZTV4NB9QFP6V7PDS39XHRAA'
                            ),
                            new OA\Property(
                                property: 'username',
                                description: 'Username of the page owner',
                                type: 'string',
                                example: 'johndoe'
                            ),
                            new OA\Property(
                                property: 'description',
                                description: 'Description of the page',
                                type: 'string',
                                example: 'This is a description of the page'
                            ),
                            new OA\Property(
                                property: 'links', type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(
                                            property: 'id',
                                            description: 'ULID of the link',
                                            type: 'string',
                                            example: '98765'
                                        ),
                                        new OA\Property(
                                            property: 'url',
                                            description: 'URL of the link',
                                            type: 'string',
                                            example: 'https://example.com'
                                        ),
                                        new OA\Property(
                                            property: 'title',
                                            description: 'Title of the link',
                                            type: 'string',
                                            example: 'Example link title'
                                        ),
                                    ],
                                    type: 'object'
                                )
                            ),
                        ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Page not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Page not found'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'An error occurred'),
                    ]
                )
            ),
        ]
    )]
    #[Route('/api/page/{username}', methods: ['GET'])]
    public function getPublicPage(string $username): JsonResponse
    {
        $pageDTO = $this->publicPageInteractor->getPublicPageByUsername(username: $username);

        return new JsonResponse([
            'status' => Status::SUCCESS,
            'data' => $pageDTO,
        ], Response::HTTP_OK);
    }
}
