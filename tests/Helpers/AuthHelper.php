<?php

namespace App\Tests\Helpers;

use App\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class AuthHelper
{
    private KernelBrowser $client;

    public function __construct(KernelBrowser $client)
    {
        $this->client = $client;
    }

    public function authorize(User $user): void
    {
        $this->client->jsonRequest('POST', '/api/auth/token', [
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ]);

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $response['token']));
    }
}
