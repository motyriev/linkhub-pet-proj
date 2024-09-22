<?php

declare(strict_types=1);

namespace App\Api\Request;

abstract class BaseRequest
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
