<?php

declare(strict_types=1);

namespace App\Tests\Resources\Fixture\Common;

use App\Tests\Resources\FakerTrait;
use App\UserPage\Domain\Factory\LinkFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LinkFixture extends Fixture
{
    use FakerTrait;

    public const REFERENCE = 'link';

    public function __construct(
        private readonly LinkFactory $linkFactory,
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $page = $this->getReference(PageFixture::REFERENCE);

        $linkCount = $this->getFaker()->numberBetween(3, 8);

        for ($i = 0; $i < $linkCount; ++$i) {
            $url = $this->getFaker()->url();
            $title = $this->getFaker()->sentence(1);

            $link = $this->linkFactory->create($page, $url, $title);

            $manager->persist($link);

            $this->addReference(self::REFERENCE.'_'.$i, $link);
        }

        $manager->flush();
    }
}
