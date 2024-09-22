<?php

declare(strict_types=1);

namespace App\Api\Request\UserPage;

use App\Api\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateMyPageRequestDTO extends BaseRequest
{
    #[Assert\NotBlank(message: 'Page Id cannot be empty')]
    public string $pageId;

    #[Assert\NotBlank(message: 'Description cannot be empty')]
    public string $description;

    #[Assert\NotBlank(message: 'LinksData cannot be empty')]
    #[Assert\Count(min: 1, minMessage: 'The page must have at least one link.')]
    #[Assert\All([
        new Assert\Collection([
            'fields' => [
                'url' => [
                    new Assert\NotBlank(message: 'Link URL cannot be empty'),
                ],
                'title' => [
                    new Assert\NotBlank(message: 'Link cannot be empty'),
                    new Assert\Type(type: 'string', message: 'Link title must be a string.'),
                ],
            ],
            'allowExtraFields' => true,
        ]),
    ])]
    public array $linksData;
}
