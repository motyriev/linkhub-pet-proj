<?php

declare(strict_types=1);

namespace App\Tests\Resources\Fixture\Common;

use App\Tests\Resources\FakerTrait;
use App\UserPage\Domain\Factory\PageFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PageFixture extends Fixture
{
    use FakerTrait;

    public const REFERENCE = 'user_page';

    public function __construct(
        private readonly PageFactory $pageFactory,
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(UserFixture::REFERENCE);

        $pageDescription = $this->getFaker()->sentence();
        $userPage = $this->pageFactory->create($user, $pageDescription);

        $manager->persist($userPage);
        $manager->flush();

        $this->addReference(self::REFERENCE, $userPage);
    }
}
