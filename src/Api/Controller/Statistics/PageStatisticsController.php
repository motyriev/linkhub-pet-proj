<?php

declare(strict_types=1);

namespace App\Api\Controller\Statistics;

use App\Api\Controller\AbstractController;
use App\Api\Request\Statistics\CreatePageVisitRequestDTO;
use App\Shared\Application\Enum\Status;
use App\Statistics\Application\UseCase\StatisticsInteractor;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageStatisticsController extends AbstractController
{
    public function __construct(
        private readonly StatisticsInteractor $statisticsInteractor,
    ) {
    }

    #[OA\Post(
        path: '/api/statistics/pages/{pageId}/visits',
        description: 'Logs a visit to a specific page, tracking the timestamp and user agent.',
        summary: 'Create a page visit statistic',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['timestamp', 'userAgent'],
                properties: [
                    new OA\Property(
                        property: 'timestamp',
                        description: 'Timestamp of the page visit in ISO 8601 format',
                        type: 'string',
                        format: 'date-time',
                        example: '2023-09-21 14:00:00'
                    ),
                    new OA\Property(
                        property: 'userAgent',
                        description: "User agent string of the visitor's browser",
                        type: 'string',
                        example: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
                    ),
                ]
            )
        ),
        tags: ['Statistics'],
        parameters: [
            new OA\Parameter(
                name: 'pageId',
                description: 'The ID of the page being visited',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: '01FZTV4NB9QFP6V7PDS39XHRAA')
            ),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Page visit recorded successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Invalid input data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid request data'),
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
    #[Route('/api/statistics/pages/{pageId}/visits', methods: ['POST'])]
    public function createPageVisit(CreatePageVisitRequestDTO $request, string $pageId): JsonResponse
    {
        $this->statisticsInteractor->createPageVisit(
            pageId: $pageId,
            timestamp: $request->timestamp,
            userAgent: $request->userAgent
        );

        return new JsonResponse([
            'status' => Status::SUCCESS,
        ], Response::HTTP_CREATED);
    }
}
