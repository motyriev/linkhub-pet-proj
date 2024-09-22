<?php

declare(strict_types=1);

namespace App\Api\Controller\UserPage;

use App\Api\Controller\AbstractController;
use App\Api\Request\UserPage\UpdateMyPageRequestDTO;
use App\Shared\Application\Enum\Status;
use App\Shared\Domain\Security\UserFetcherInterface;
use App\UserPage\Application\UseCase\MyPageInteractor;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyPageController extends AbstractController
{
    public function __construct(
        private readonly UserFetcherInterface $userFetcher,
        private readonly MyPageInteractor $myPageInteractor,
    ) {
    }

    #[OA\Get(
        path: '/api/user/page',
        description: "Retrieves the authenticated user's page along with visit statistics and link click statistics. Requires authentication via Bearer token.",
        summary: "Get the authenticated user's page with statistics",
        security: [
            ['bearerAuth' => []],
        ],
        tags: ['User Page'],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Page retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'data', properties: [
                            new OA\Property(property: 'page', properties: [
                                new OA\Property(
                                    property: 'pageId',
                                    type: 'string',
                                    example: '01J8A77D0TMRMPMTSW17ZPKT0E'
                                ),
                                new OA\Property(property: 'description', type: 'string', example: 'My Page'),
                                new OA\Property(property: 'username', type: 'string', example: 'user123'),
                                new OA\Property(
                                    property: 'links', type: 'array',
                                    items: new OA\Items(properties: [
                                        new OA\Property(
                                            property: 'id',
                                            type: 'string',
                                            example: '01J8WWWEQMRMPMTSW17ZPKT0E'
                                        ),
                                        new OA\Property(property: 'title', type: 'string', example: 'github'),
                                        new OA\Property(
                                            property: 'url', type: 'string', example: 'https://example.com'
                                        ),
                                    ],
                                        type: 'object'
                                    )
                                ),
                            ],
                                type: 'object'
                            ),
                            new OA\Property(property: 'statistics', properties: [
                                new OA\Property(
                                    property: 'pageVisits', type: 'array',
                                    items: new OA\Items(properties: [
                                        new OA\Property(
                                            property: 'id', type: 'string', example: '01J23320TMRMPMTSWRR0E'
                                        ),
                                        new OA\Property(
                                            property: 'pageId',
                                            type: 'string',
                                            example: '01J8A77D0TMRMPMTSW17ZPKT0E'
                                        ),
                                        new OA\Property(
                                            property: 'timestamp',
                                            type: 'string',
                                            format: 'date-time',
                                            example: '2024-09-12T15:03:23Z'
                                        ),
                                        new OA\Property(property: 'userAgent', type: 'string', example: 'Mozilla/5.0'),
                                    ],
                                        type: 'object'
                                    )
                                ),
                                new OA\Property(
                                    property: 'linkClicks', type: 'array',
                                    items: new OA\Items(properties: [
                                        new OA\Property(
                                            property: 'id', type: 'string', example: '02J233455MRMPMTWERR0E'
                                        ),
                                        new OA\Property(
                                            property: 'linkId',
                                            type: 'string',
                                            example: '01J8WWWEQMRMPMTSW17ZPKT0E'
                                        ),
                                        new OA\Property(
                                            property: 'pageId',
                                            type: 'string',
                                            example: '01J8A77D0TMRMPMTSW17ZPKT0E'
                                        ),
                                        new OA\Property(
                                            property: 'timestamp',
                                            type: 'string',
                                            format: 'date-time',
                                            example: '2024-09-12T15:05:45Z'
                                        ),
                                        new OA\Property(property: 'userAgent', type: 'string', example: 'Mozilla/5.0'),
                                    ],
                                        type: 'object'
                                    )
                                ),
                            ],
                                type: 'object'
                            ),
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
                description: 'Internal server error',
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
    #[Route('/api/user/page', methods: ['GET'])]
    public function getMyPage(): JsonResponse
    {
        $user = $this->userFetcher->getAuthUser();

        $pageWithStatsDTO = $this->myPageInteractor->getMyPageWithStatistics(userId: $user->getId());

        return new JsonResponse([
            'status' => Status::SUCCESS,
            'data' => [
                'page' => $pageWithStatsDTO->getPageDTO(),
                'statistics' => [
                    'pageVisits' => $pageWithStatsDTO->getPageVisitsDTOs(),
                    'linkClicks' => $pageWithStatsDTO->getLinkClicksDTOs(),
                ],
            ],
        ], Response::HTTP_OK);
    }

    #[OA\Put(
        path: '/api/user/page',
        description: "Updates the authenticated user's page with a new description and links. Requires authentication via Bearer token.",
        summary: "Update the authenticated user's page",
        security: [
            ['bearerAuth' => []],
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'pageId',
                        description: 'ULID of the page',
                        type: 'string',
                        example: '01FZTV4NB9QFP6V7PDS39XHRAA'
                    ),
                    new OA\Property(
                        property: 'description',
                        description: 'Description of the page',
                        type: 'string',
                        example: 'Updated page description'
                    ),
                    new OA\Property(
                        property: 'linksData',
                        description: 'Array of links associated with the page',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    description: 'ULID of the link',
                                    type: 'string',
                                    example: '01FZTV4NB9QFP6V7PDS39XHRAA'
                                ),
                                new OA\Property(
                                    property: 'url',
                                    description: 'Link URL',
                                    type: 'string',
                                    example: 'https://example.com'
                                ),
                                new OA\Property(
                                    property: 'title',
                                    description: 'Link title',
                                    type: 'string',
                                    example: 'Example link title'
                                ),
                            ],
                            type: 'object'
                        )
                    ),
                ],
                type: 'object'
            )
        ),
        tags: ['User Page'],
        responses: [
            new OA\Response(
                response: Response::HTTP_ACCEPTED,
                description: 'Page updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Page updated successfully'),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Validation Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation Error'),
                        new OA\Property(
                            property: 'errors',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'field',
                                        type: 'string',
                                        example: 'pageId'
                                    ),
                                    new OA\Property(
                                        property: 'message',
                                        type: 'string',
                                        example: 'Page Id cannot be empty'
                                    ),
                                ],
                                type: 'object'
                            )
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
                description: 'Internal server error',
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
    #[Route('/api/user/page', methods: ['PUT'])]
    public function updateMyPage(UpdateMyPageRequestDTO $request): JsonResponse
    {
        $this->myPageInteractor->updateMyPage(
            pageId: $request->pageId,
            description: $request->description,
            linksData: $request->linksData
        );

        return new JsonResponse(['message' => 'Page updated successfully', 'status' => Status::SUCCESS],
            Response::HTTP_ACCEPTED);
    }
}
