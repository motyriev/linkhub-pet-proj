<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\UserPage;

use App\Tests\Helpers\AuthHelper;
use App\Tests\Resources\FakerTrait;
use App\Tests\Resources\Fixture\Common\LinkFixture;
use App\Tests\Resources\Fixture\Common\PageFixture;
use App\Tests\Resources\Fixture\Common\UserFixture;
use App\Tests\Resources\Fixture\Custom\ClickHousePageVisitFixture;
use App\User\Domain\Entity\User;
use App\UserPage\Domain\Entity\Page;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyPageControllerTest extends WebTestCase
{
    use FakerTrait;

    protected AbstractDatabaseTool $databaseTool;
    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testGetMyPage(): void
    {
        $ex = $this->databaseTool->loadFixtures([
            UserFixture::class,
            PageFixture::class,
            LinkFixture::class,
        ]);

        $authHelper = new AuthHelper($this->client);

        $user = $ex->getReferenceRepository()->getReference(UserFixture::REFERENCE, User::class);
        $page = $ex->getReferenceRepository()->getReference(PageFixture::REFERENCE, Page::class);

        $authHelper->authorize($user);

        $pageVisitFixture = static::getContainer()->get(ClickHousePageVisitFixture::class);
        $pageVisitFixture->load($page->getId());

        $this->client->request('GET', '/api/user/page');

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals('success', $response['status']);
        $this->assertNotEmpty($response['data']['page']['pageId']);
        $this->assertTrue(isset($response['data']['statistics']['pageVisits']));
        $this->assertTrue(isset($response['data']['statistics']['linkClicks']));
    }

    public function testUpdateMyPage(): void
    {
        $ex = $this->databaseTool->loadFixtures([
            UserFixture::class,
            PageFixture::class,
            LinkFixture::class,
        ]);

        $authHelper = new AuthHelper($this->client);

        $user = $ex->getReferenceRepository()->getReference(UserFixture::REFERENCE, User::class);
        $page = $ex->getReferenceRepository()->getReference(PageFixture::REFERENCE, Page::class);
        $authHelper->authorize($user);

        $this->client->request('GET', '/api/user/page');
        $response = json_decode($this->client->getResponse()->getContent(), true);

        $oldLinks = $response['data']['page']['links'];

        $pageNewDescription = 'Updated page description';
        $newData = [
            'pageId' => $page->getId(),
            'description' => $pageNewDescription,
            'linksData' => [
                ['id' => $oldLinks[0]['id'], 'url' => 'https://updated0.com', 'title' => $oldLinks[0]['title']],
                ['id' => $oldLinks[1]['id'], 'url' => 'https://updated1.com', 'title' => 'new title'],
                ['url' => 'https://newlink.com', 'title' => 'New link'],
            ],
        ];

        $this->client->jsonRequest('PUT', '/api/user/page', $newData);
        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals('success', $response['status']);

        $this->client->request('GET', '/api/user/page');
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($response['data']['page']['description'], $pageNewDescription);
        $this->assertCount(3, $response['data']['page']['links']);

        $this->assertEquals($pageNewDescription, $response['data']['page']['description']);

        $this->assertCount(3, $response['data']['page']['links']);

        foreach ($newData['linksData'] as $index => $linkData) {
            $this->assertEquals($linkData['url'], $response['data']['page']['links'][$index]['url']);
            $this->assertEquals($linkData['title'], $response['data']['page']['links'][$index]['title']);

            if (isset($linkData['id'])) {
                $this->assertEquals($linkData['id'], $response['data']['page']['links'][$index]['id']);
            }
        }
    }
}
