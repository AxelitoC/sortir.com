<?php

namespace App\DataFixtures;

use App\Entity\Site;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this ->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        //TODO : Créer une boucle de création d'user
        $site = new Site();
        $site2 = new Site();
        $user = new User();
        $user2 = new User();
        $site -> setNom('Campus de Niort');
        $site2 -> setNom('Campus de Nantes');
        $user -> setEmail('admin@gmail.com');
        $user2 -> setEmail('user@gmail.com');
        $user -> setRoles(['ROLE_ADMIN']);
        $user2 -> setRoles(['ROLE_USER']);
        $user -> setNom('Bibi');
        $user2 -> setNom('bobo');
        $user -> setPrenom('Labrique');
        $user2 -> setPrenom('Laroche');
        $user -> setPseudo('Briquet');
        $user2 -> setPseudo('Rocher');
        $user -> setTelephone('0546137956');
        $user2 -> setTelephone('1549879857');
        $user -> setActif(true);
        $user2 -> setActif(true);
        $user -> setPassword($this ->passwordHasher->hashPassword($user, '123'));
        $user2 -> setPassword($this ->passwordHasher->hashPassword($user, '456'));
        $user -> setSite($site);
        $user2 -> setSite($site2);
        // $product = new Product();
        $manager->persist($site);
        $manager->persist($site2);
        $manager->persist($user);
        $manager->persist($user2);
        $manager->flush();
    }
}
