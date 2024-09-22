<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\UserPage;

use App\Tests\Resources\Fixture\Common\LinkFixture;
use App\Tests\Resources\Fixture\Common\PageFixture;
use App\Tests\Resources\Fixture\Common\UserFixture;
use App\User\Domain\Entity\User;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicPageControllerTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testGetPublicPage()
    {
        $ex = $this->databaseTool->loadFixtures([
            UserFixture::class,
            PageFixture::class,
            LinkFixture::class,
        ]);

        $user = $ex->getReferenceRepository()->getReference(UserFixture::REFERENCE, User::class);

        $this->client->request('GET', "/api/page/{$user->getUsername()}");

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('pageId', $response['data']);
        $this->assertArrayHasKey('username', $response['data']);
        $this->assertArrayHasKey('description', $response['data']);
        $this->assertArrayHasKey('links', $response['data']);
    }
}
