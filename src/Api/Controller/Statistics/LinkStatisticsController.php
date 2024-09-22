<?php

declare(strict_types=1);

namespace App\Api\Controller\Statistics;

use App\Api\Controller\AbstractController;
use App\Api\Request\Statistics\CreateLinkClickRequestDTO;
use App\Shared\Application\Enum\Status;
use App\Statistics\Application\UseCase\StatisticsInteractor;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LinkStatisticsController extends AbstractController
{
    public function __construct(
        private readonly StatisticsInteractor $statisticsInteractor,
    ) {
    }

    #[OA\Post(
        path: '/api/statistics/links/{linkId}/clicks',
        description: 'Logs a click for a specific link and tracks the page, timestamp, and user agent.',
        summary: 'Create a new link click statistic',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['pageId', 'timestamp', 'userAgent'],
                properties: [
                    new OA\Property(
                        property: 'pageId',
                        description: 'The ID of the page associated with the link',
                        type: 'string',
                        example: '01FZTV4NB9QFP6V7PDS39XHRAA'
                    ),
                    new OA\Property(
                        property: 'timestamp',
                        description: 'Timestamp of the click event',
                        type: 'string',
                        format: 'date-time',
                        example: '2023-09-21 14:00:00'
                    ),
                    new OA\Property(
                        property: 'userAgent',
                        description: 'User agent string of the client making the request',
                        type: 'string',
                        example: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
                    ),
                ]
            )
        ),
        tags: ['Statistics'],
        parameters: [
            new OA\Parameter(
                name: 'linkId',
                description: 'The ID of the link',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: '01FZTV4NB9QFP6V7PDS39XHRAA')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Link click accepted',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
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
    #[Route('/api/statistics/links/{linkId}/clicks', methods: ['POST'])]
    public function createLinkClick(CreateLinkClickRequestDTO $request, string $linkId): JsonResponse
    {
        $this->statisticsInteractor->createLinkClick(
            linkId: $linkId,
            pageId: $request->pageId,
            timestamp: $request->timestamp,
            userAgent: $request->userAgent
        );

        return new JsonResponse([
            'status' => Status::SUCCESS,
        ], Response::HTTP_ACCEPTED);
    }
}
