<?php

namespace App\Controller;

use App\Form\ModifUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/user", name="user_")
 */

class UserController extends AbstractController
{
    /**
     * @Route("/modif", name="modif")
     * @param SluggerInterface $slugger
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function modification(SluggerInterface $slugger, \Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $form = $this->createForm(ModifUserType::class, $user);
        $form->handleRequest($request);
        $originalPassword = $user ->getPassword();
        if ($form->isSubmitted() && $form->isValid()) {
            //encode the plain password
            if(!empty($form['password']->getData())) {

                $user->setPassword($passwordHasher->hashPassword($user, $form['password']->getData()));

            } else {

                $user->setPassword($originalPassword);

            }
            $photo = $form->get('photo_profil')->getData();

            if($photo){
                $photoOriginal = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($photoOriginal);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$photo->guessExtension();

                try {
                    $photo->move(
                        $this->getParameter('images_directory'),
                        $newFileName
                    );
                }catch(FileException $e){
                    $this->addFlash("Une erreur est survenue durant le téléchargement du fichier");
                }
                $user->setPhotoProfil($newFileName);
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_modif');
        }
        return $this->render('user/modif_user.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="user.profile")
     * @param $id
     * @param UserRepository $uR
     * @return Response
     */
    public function profile($id, UserRepository $uR): Response {

        $user = $uR->findOneBy(['id' => $id, 'actif' => true]);

        if ($user === null) {
           throw $this->createNotFoundException();
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }
}