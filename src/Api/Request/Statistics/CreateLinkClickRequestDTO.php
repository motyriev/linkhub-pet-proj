<?php

declare(strict_types=1);

namespace App\Api\Request\Statistics;

use App\Api\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateLinkClickRequestDTO extends BaseRequest
{
    #[Assert\NotBlank(message: 'Page ID cannot be empty')]
    #[Assert\Type('string', message: 'Page ID must be a string')]
    public string $pageId;

    #[Assert\NotBlank(message: 'Timestamp cannot be empty')]
    #[Assert\DateTime(format: 'Y-m-d H:i:s', message: "Invalid timestamp format, expected 'Y-m-d H:i:s'")]
    public string $timestamp;

    #[Assert\NotBlank(message: 'User Agent cannot be empty')]
    public ?string $userAgent;
}
