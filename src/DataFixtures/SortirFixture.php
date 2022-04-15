<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Date;

class SortirFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //todo: Création d'une boucle de création de sortie
        $lieu = new Lieu();
        $ville = new Ville();
        $ville->setNom('Une ville');
        $ville->setCodePostal('87655');
        $lieu->setNom('Un lieu');
        $lieu->setRue('Une rue');
        $lieu->setVille($ville);
        $site = new Site();
        $site -> setNom('eni');
        $sortie = new Sortie();
        $etat = new Etat();
        $sortie2 = new Sortie();
        $etat2 = new Etat();
        $sortie->setNom('Job dating');
        $sortie2->setNom('Soirée CTF');
        $sortie->setDateHeureDebut(new \DateTime('08-07-2022 15:00'));
        $sortie2->setDateHeureDebut(new \DateTime('18-04-2022 19:00'));
        $sortie->setDuree('3');
        $sortie2->setDuree('6');
        $sortie->setDateLimiteInscription(new \DateTime('15-08-2022'));
        $sortie2->setDateLimiteInscription(new \DateTime('18-05-2022'));
        $sortie->setNbInscriptionsMax('50');
        $sortie2->setNbInscriptionsMax('60');
        $sortie->setInfoSortie('Rencontre stagiaire entreprise');
        $sortie2->setInfoSortie('Compétiton de Cybersécurité par équipes de 3');
        $etat->setLibelle('Créée');
        $sortie->setEtat($etat);
        $etat2->setLibelle('Créée');
        $sortie2->setEtat($etat2);
        $sortie->setSite($site);
        $sortie2->setSite($site);
        $sortie->setLieu($lieu);
        $sortie2->setLieu($lieu);
        $sortie->setOnline(true);
        $sortie2->setOnline(false);

        $manager->persist($site);
        $manager->persist($lieu);
        $manager->persist($ville);
        $manager->persist($etat);
        $manager->persist($sortie);
        $manager->persist($etat2);
        $manager->persist($sortie2);

        $manager->flush();
    }

}
