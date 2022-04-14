<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $etat1 = new Etat();
        $etat1->setLibelle("Créée");
        $etat2 = new Etat();
        $etat2->setLibelle("Ouverte");
        $etat3 = new Etat();
        $etat3->setLibelle("Clôturée");
        $etat4 = new Etat();
        $etat4->setLibelle("Activité en cours");
        $etat5 = new Etat();
        $etat5->setLibelle("Passée");
        $etat6 = new Etat();
        $etat6->setLibelle("Annulée");

        $manager->persist($etat1);
        $manager->persist($etat2);
        $manager->persist($etat3);
        $manager->persist($etat4);
        $manager->persist($etat5);
        $manager->persist($etat6);
        $manager->flush();
    }
}
