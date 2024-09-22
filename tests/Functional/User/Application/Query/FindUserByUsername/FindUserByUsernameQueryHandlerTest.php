<?php

declare(strict_types=1);

namespace App\Tests\Functional\User\Application\Query\FindUserByUsername;

use App\Shared\Application\Query\QueryBusInterface;
use App\Tests\Resources\Fixture\Common\UserFixture;
use App\User\Application\DTO\UserDTO;
use App\User\Application\UseCase\Query\FindUserByUsername\FindUserByUsernameQuery;
use App\User\Domain\Entity\User;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindUserByUsernameQueryHandlerTest extends WebTestCase
{
    private QueryBusInterface $queryBus;
    private AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->queryBus = static::getContainer()->get(QueryBusInterface::class);
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testUserFound(): void
    {
        $ex = $this->databaseTool->loadFixtures([UserFixture::class]);
        $user = $ex->getReferenceRepository()->getReference(UserFixture::REFERENCE, User::class);

        $query = new FindUserByUsernameQuery($user->getUsername());

        $userDTO = $this->queryBus->execute($query);

        $this->assertInstanceOf(UserDTO::class, $userDTO);
    }
}
