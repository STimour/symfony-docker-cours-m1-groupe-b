<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Song;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $song = new Song();
        $song->setName(name: "Roll The Dice");
        $song->setArtiste(artiste: "Kiss Husky");

        $manager->persist($song);
        
        $manager->flush();
    }
}
