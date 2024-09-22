<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\Statistics;

use App\Statistics\Domain\Entity\LinkClick;
use App\Statistics\Domain\Repository\LinkClickRepositoryInterface;
use App\Tests\Resources\FakerTrait;
use App\Tests\Resources\Fixture\Common\LinkFixture;
use App\Tests\Resources\Fixture\Common\PageFixture;
use App\Tests\Resources\Fixture\Common\UserFixture;
use App\UserPage\Domain\Entity\Page;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LinkStatisticsControllerTest extends WebTestCase
{
    use FakerTrait;

    protected AbstractDatabaseTool $databaseTool;
    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->linkClickRepo = static::getContainer()->get(LinkClickRepositoryInterface::class);
    }

    public function testTrackLinkClick()
    {
        $ex = $this->databaseTool->loadFixtures([
            UserFixture::class,
            PageFixture::class,
            LinkFixture::class,
        ]);

        $page = $ex->getReferenceRepository()->getReference(PageFixture::REFERENCE, Page::class);

        $timestamp = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        $userAgent = $this->getFaker()->userAgent();
        $linkId = $page->getLinks()->first()->getId();
        $pageId = $page->getId();

        $this->client->jsonRequest(
            'POST',
            "/api/statistics/links/{$linkId}/clicks",
            [
                'pageId' => $pageId,
                'timestamp' => $timestamp,
                'userAgent' => $userAgent,
            ]
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals('success', $response['status']);

        $linkClicks = $this->linkClickRepo->getByCriteria(
            ['link_id' => $linkId, 'timestamp' => $timestamp, 'user_agent' => $userAgent],
        );

        $this->assertInstanceOf(LinkClick::class, $linkClicks[0]);
    }
}
