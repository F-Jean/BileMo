<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\DataFixtures\ClientFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach($this->getReference(ClientFixtures::CLIENTS_REFERENCE) as $client)
        {
            for($u = 1; $u <= 20; $u++){
                $user = new User();
                $user->setName("User $u")
                ->setLastname("Lastname $u")
                ->setAdress("Adress $u")
                ->setCreditCard("Card number $u")
                ->setRegisteredAt(new \DateTimeImmutable())
                ->setClient($this->getReference(ClientFixtures::CLIENT_REFERENCE));
    
                $manager->persist($user);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixtures::class,
        ];
    }
}
