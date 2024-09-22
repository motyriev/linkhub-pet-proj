<?php

declare(strict_types=1);

namespace App\Tests\Functional\User\Application\Command\CreateUser;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\Security\AuthUserInterface;
use App\Tests\Resources\FakerTrait;
use App\User\Application\UseCase\Command\CreateUser\CreateUserCommand;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateUserCommandHandlerTest extends WebTestCase
{
    use FakerTrait;

    private UserRepositoryInterface $userRepository;
    private CommandBusInterface $commandBus;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = static::getContainer()->get(UserRepositoryInterface::class);
        $this->commandBus = static::getContainer()->get(CommandBusInterface::class);
    }

    public function testUserCreatedSuccessfully(): void
    {
        $email = $this->getFaker()->email();
        $username = $this->getFaker()->userName();
        $password = $this->getFaker()->password();

        $uId = $this->commandBus->execute(new CreateUserCommand($email, $username, $password));

        $user = $this->userRepository->findById($uId);
        $this->assertInstanceOf(AuthUserInterface::class, $user);
    }
}
