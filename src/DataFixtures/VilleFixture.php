<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++){
            $lieu = new Lieu();
            $ville = new Ville();
            $ville->setNom('Ville'.$i);
            $ville->setCodePostal(mt_rand(10000, 99000));
            $lieu->setNom('Lieu'.$i);
            $lieu->setRue('Rue'.$i);
            $lieu->setVille($ville);
            $manager->persist($ville);
            $manager->persist($lieu);
        }

        $manager->flush();
    }
}
