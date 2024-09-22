<?php

declare(strict_types=1);

namespace App\Tests\Resources;

use Faker\Factory;
use Faker\Generator;

trait FakerTrait
{
    public function getFaker(): Generator
    {
        return Factory::create();
    }
}
