<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SortirFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //todo: Création d'une boucle de création de sortie

        $sortie = new Sortie();
        $etat = new Etat();
        $sortie2 = new Sortie();
        $etat2 = new Etat();
        $sortie->setNom('Job dating');
        $sortie2->setNom('Soirée CTF');
        $sortie->setDateHeureDebut('08/07/2022 15:00');
        $sortie2->setDateHeureDebut('18/04/2022');
        $sortie->setDuree('3');
        $sortie2->setDuree('6');
        $sortie->setDateLimiteInscription('12/07/2022');
        $sortie2->setDateLimiteInscription('18/05/2022');
        $sortie->setNbInscriptionsMax('50');
        $sortie2->setNbInscriptionsMax('60');
        $sortie->setInfoSortie('Rencontre stagiaire entreprise');
        $sortie2->setInfoSortie('Compétiton de Cybersécurité par équipes de 3');
        $etat->setLibelle('Créée');
        $sortie->setEtat($etat);
        $etat2->setLibelle('Créée');
        $sortie2->setEtat($etat2);

        $manager->persist($etat);
        $manager->persist($sortie);
        $manager->persist($etat2);
        $manager->persist($sortie2);

        $manager->flush();
    }

}
