<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\Statistics;

use App\Statistics\Domain\Entity\PageVisit;
use App\Statistics\Domain\Repository\PageVisitRepositoryInterface;
use App\Tests\Resources\FakerTrait;
use App\Tests\Resources\Fixture\Common\LinkFixture;
use App\Tests\Resources\Fixture\Common\PageFixture;
use App\Tests\Resources\Fixture\Common\UserFixture;
use App\UserPage\Domain\Entity\Page;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageStatisticsControllerTest extends WebTestCase
{
    use FakerTrait;

    protected AbstractDatabaseTool $databaseTool;
    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->pageVisitRepository = static::getContainer()->get(PageVisitRepositoryInterface::class);
    }

    public function testTrackPageVisit()
    {
        $ex = $this->databaseTool->loadFixtures([
            UserFixture::class,
            PageFixture::class,
            LinkFixture::class,
        ]);

        $page = $ex->getReferenceRepository()->getReference(PageFixture::REFERENCE, Page::class);

        $timestamp = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        $userAgent = $this->getFaker()->userAgent();
        $pageId = $page->getId();

        $this->client->jsonRequest(
            'POST',
            "/api/statistics/pages/{$pageId}/visits",
            [
                'timestamp' => $timestamp,
                'userAgent' => $userAgent,
            ]
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals('success', $response['status']);

        $pageVisits = $this->pageVisitRepository->getByCriteria(
            ['page_id' => $pageId, 'timestamp' => $timestamp, 'user_agent' => $userAgent],
        );

        $this->assertInstanceOf(PageVisit::class, $pageVisits[0]);
    }
}
