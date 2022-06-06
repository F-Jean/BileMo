<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Client;
use App\DataFixtures\ClientFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $clients = $manager->getRepository(Client::class)->findAll();
        foreach ($clients as $client) {
            for ($u = 1; $u <= 20; $u++) {
                $user = new User();
                $user->setName("User $u")
                    ->setLastname("Lastname $u")
                    ->setAdress("Adress $u")
                    ->setCreditCard("5412597435621548")
                    ->setRegisteredAt(new \DateTimeImmutable('2022-01-01T10:00:00+00:00'))
                    ->setClient($client);

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
