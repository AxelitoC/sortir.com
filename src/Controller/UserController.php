<?php

namespace App\Controller;

use App\Form\ModifUserType;
use Doctrine\ORM\EntityManagerInterface;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user_")
 */

class UserController extends AbstractController
{
    /**
     * @Route("/modif", name="modif")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function modification(\Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
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

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_modif');
        }
        return $this->render('user/modif_user.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
