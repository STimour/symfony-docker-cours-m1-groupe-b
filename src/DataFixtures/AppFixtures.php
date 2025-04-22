<?php

namespace App\DataFixtures;

use App\Entity\Song; // Assurez-vous que la classe Song est correctement importée
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR'); // Correction de la syntaxe pour Faker
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $song = new Song();
            $song->setName($this->faker->sentence(3)); // Génère un titre aléatoire
            $song->setArtiste($this->faker->name); // Génère un nom d'artiste aléatoire

            $manager->persist($song);
        }

        $manager->flush(); // Sauvegarde toutes les entités en base de données
    }
}
