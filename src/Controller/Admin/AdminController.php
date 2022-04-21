<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdminCreateUserType;
use App\Form\GererSiteType;
use App\Form\GererVilleType;
use App\Repository\SiteRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/createUser", name="createUser")
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(AdminCreateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_createUser');
        }

        return $this->render('admin/createUser.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route ("/gererVille", name="gerer_ville")
     * @param EntityManagerInterface $em
     * @param VilleRepository $vr
     * @param Request $request
     * @return RedirectResponse
     */
    public function gererVille(EntityManagerInterface $em, VilleRepository $vr, Request $request): Response{

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(GererSiteType::class);
        $form->handleRequest($request);
        $ville = $vr->findBy(['nom' => $form->get('nom')->getData()]);

        return $this->render('admin/gererVille.html.twig', [
            'form' => $form->createView(),
            'ville' => $ville,
        ]);
    }

    /**
     * @Route ("/gererSite", name="gerer_site")
     * @param EntityManagerInterface $em
     * @param SiteRepository $sr
     * @param Request $request
     * @return RedirectResponse
     */
    public function gererSite(EntityManagerInterface $em, SiteRepository $sr, Request $request):Response{

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(GererSiteType::class);
        $form->handleRequest($request);
        $ville = $sr->findBy(['nom' => $form->get('nom')->getData()]);

        return $this->render('admin/gererSite.html.twig', [
            'form' => $form->createView(),
            'ville' => $ville,
        ]);
    }
}
