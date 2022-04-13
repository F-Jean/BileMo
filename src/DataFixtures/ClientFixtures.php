<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Client;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{
    private $passwordHasher;
    public const CLIENT_REFERENCE = 'client';
    public const CLIENTS_REFERENCE = 'clients';

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $clients = [];

        for($i = 1; $i <= 5; $i++){
            $client = new Client();
            $client->setName("Client $i")
            ->setEmail("$i@b2b.com")
            ->setEnabled("1")
            ->setRegisteredAt(new \DateTimeImmutable())
            ->setPassword($this->passwordHasher->hashPassword($client, 'password'));

            $manager->persist($client);
            array_push($clients, $client);
        }

        $manager->flush();

        $this->addReference(self::CLIENT_REFERENCE, $client);
        $this->addReference(self::CLIENTS_REFERENCE, $clients);
    }
}
