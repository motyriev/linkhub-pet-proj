<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    // async

    /**
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    public function dispatch(CommandInterface $command): void
    {
        $this->messageBus->dispatch($command);
    }

    // sync
    public function execute(CommandInterface $command): mixed
    {
        return $this->handle($command);
    }
}
