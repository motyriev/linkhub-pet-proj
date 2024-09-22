<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\User;

use App\Tests\Resources\FakerTrait;
use App\Tests\Resources\Fixture\Common\UserFixture;
use App\User\Domain\Entity\User;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
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

    public function testLogin(): void
    {
        $ex = $this->databaseTool->loadFixtures([UserFixture::class]);
        $user = $ex->getReferenceRepository()->getReference(UserFixture::REFERENCE, User::class);

        $this->client->jsonRequest(
            'POST',
            '/api/auth/token',
            ['username' => $user->getUsername(), 'password' => $user->getPassword()]
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $response['token']));

        $this->client->request('GET', '/api/users/me');

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals($user->getUsername(), $response['data']['username']);
    }

    public function testRegister(): void
    {
        $email = $this->getFaker()->email();
        $username = $this->getFaker()->userName();
        $password = $this->getFaker()->password();

        $this->client->jsonRequest(
            'POST',
            '/api/users/register',
            ['username' => $username, 'email' => $email, 'password' => $password]
        );

        $response = $this->client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('User registered successful', $data['message']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client, $this->databaseTool);
    }
}
