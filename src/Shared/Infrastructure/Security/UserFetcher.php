<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Domain\Security\AuthUserInterface;
use App\Shared\Domain\Security\UserFetcherInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserFetcher implements UserFetcherInterface
{
    public function __construct(private readonly Security $security)
    {
    }

    public function getAuthUser(): AuthUserInterface
    {
        $user = $this->security->getUser();

        if (!$user instanceof AuthUserInterface) {
            throw new UnauthorizedHttpException('Bearer', 'JWT Token not found');
        }

        return $user;
    }
}
