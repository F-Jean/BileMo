<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // commented in order to perform functionnal test
        // $randomPriceWT = rand(142.95,684.95);
        // $randomPriceATI = (($randomPriceWT*20)/100)+$randomPriceWT;
        $slugger = new AsciiSlugger();

        for ($i = 1; $i <= 20; $i++) {
            $product = new Product();
            $product->setName("Smartphone $i")
                ->setdescription("Description produit $i")
                ->setImage('phone.jpg')
                ->setSlug($slugger->slug($product->getName())->lower()->toString())
                // ->setPriceWT($randomPriceWT)
                // ->setPriceATI($randomPriceATI)
                ->setPriceWT(439.60)
                ->setPriceATI(550)
                ->setAddedAt(new \DateTimeImmutable('2022-01-01T10:00:00+00:00'));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
