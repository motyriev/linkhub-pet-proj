<?php

declare(strict_types=1);

namespace App\Tests\Resources\Fixture\Common;

use App\Tests\Resources\FakerTrait;
use App\User\Domain\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    use FakerTrait;

    public const REFERENCE = 'user';

    public function __construct(private readonly UserFactory $userFactory)
    {
    }

    public function load(ObjectManager $manager)
    {
        $email = $this->getFaker()->email();
        $username = $this->getFaker()->userName();
        $password = $this->getFaker()->password();

        $user = $this->userFactory->create($email, $username, $password);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::REFERENCE, $user);
    }
}
