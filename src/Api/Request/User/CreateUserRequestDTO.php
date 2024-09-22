<?php

declare(strict_types=1);

namespace App\Api\Request\User;

use App\Api\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequestDTO extends BaseRequest
{
    #[Assert\NotBlank(message: 'Email cannot be empty')]
    public string $email;

    #[Assert\NotBlank(message: 'Username cannot be empty')]
    public string $username;

    #[Assert\NotBlank(message: 'Password cannot be empty')]
    public string $password;
}
