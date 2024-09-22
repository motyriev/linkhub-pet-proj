<?php

declare(strict_types=1);

namespace App\Tests\Functional\User\Infrastructure\Repository;

use App\Tests\Resources\Fixture\Common\UserFixture;
use App\User\Domain\Repository\UserRepositoryInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    private UserRepositoryInterface $userRepository;
    private AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = static::getContainer()->get(UserRepositoryInterface::class);
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testUserFoundSuccessfully(): void
    {
        $ex = $this->databaseTool->loadFixtures([UserFixture::class]);
        $user = $ex->getReferenceRepository()->getReference(UserFixture::REFERENCE);

        $existingUser = $this->userRepository->findById($user->getId());

        $this->assertEquals($user->getId(), $existingUser->getId());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
