<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\ModifierSortieFormType;
use App\Form\NewSortieFormType;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="new_sortie")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EtatRepository $er
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, EtatRepository $er): Response
    {
        if (!$this->getUser()) {
        return $this->redirectToRoute('app_login');
        }

        $sortie = new Sortie();
        $form = $this->createForm(NewSortieFormType::class, $sortie);
        $form->handleRequest($request);
        $etat = $er->findOneBy(['libelle' => 'Créee']);

        if ($form->isSubmitted() && $form->isValid() ) {

            $sortie->setSite($this->getUser()->getSite());
            $sortie->setEtat($etat);
            $sortie->setOrganisateur($this->getUser());

        if($form->get('online')->isClicked()){
            $sortie->setOnline(true);
        }else{
            $sortie->setOnline(false);
        }
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('affichage');
        }


        return $this->render('sortie/new_sortie.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="modifier_sortie")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EtatRepository $er
     * @return Response
     */
    public function modification(\Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $em, SortieRepository $sortieRepository, int $id): Response
    {
        $sortie=$sortieRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(ModifierSortieFormType::class, $sortie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form ->isValid()){
            $this->redirectToRoute('affichage');
        }

        return $this->render('sortie/modifier_sortie.html.twig', [
            'form'=>$form->createView(),
            'sortie' => $sortie
        ]);


    }



/**
     * @Route("/afficher/{id}", name="afficher_sortie")
     * @param $id
     * @param SortieRepository $sr
     * @return RedirectResponse|Response
     */
    public function show(int $id, SortieRepository $sr): Response{

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $sortie = $sr->findOneBy(['id' => $id]);

        $users = $sortie->getUser();

        return $this->render('sortie/afficher_sortie.html.twig', [
            'sortie' => $sortie,
            'users'=>$users
        ]);
    }

    /**
     * @Route("/inscrire/{id}", name="inscrire")
     * @return Response
     */
    public function inscrire(int $id, SortieRepository $r, EntityManagerInterface $em): Response {

        // une requete pour trouver la sortie par rapport a son identifiant
        $sortie = $r->findOneBy(['id' => $id]);

        if (!$sortie) {
            throw new NotFoundHttpException();
        }

        if ($sortie->getOrganisateur()->getId() === $this->getUser()->getId()) {
            $this->addFlash('danger', 'Tu es le créateur de la sortie. Inscription impossible !');

            return $this->redirectToRoute('affichage');
        }

        $sortie->addUser($this->getUser());

        $em->persist($sortie);
        $em->flush();

        // Vérifier que l'utilisateur n'est pas acutellement isncrit

        $this->addFlash('success','Vous êtes bien inscrit à la sortie : ' . $sortie->getNom());

        return $this->redirectToRoute('affichage');
    }

    /**
     * @Route("/publier/{id}", name="publier")
     * @param int $id
     * @return Response
     */
    public function publier(int $id, SortieRepository $r, EntityManagerInterface $em): Response {

        $sortie = $r->findOneBy(['id' => $id]);

        if (!$sortie) {
            throw new NotFoundHttpException();
        }

        if ($sortie->getOrganisateur()->getUserIdentifier() != $this->getUser()->getUserIdentifier()) {
            throw new NotFoundHttpException();
        }

        $sortie->setOnline(true);
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Votre sortie à bien été publier');
        return $this->redirectToRoute('affichage');
    }
}
