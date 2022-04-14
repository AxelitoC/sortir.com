<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\NewSortieFormType;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $sortie->addUser($this->getUser());//Lier l'utilisateur à une sortie

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
     * @Route("/afficher/{id}", name="afficher")
     * @param $id
     * @param SortieRepository $sr
     * @return RedirectResponse|Response
     */
    public function show(int $id, SortieRepository $sr): Response{

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $sortie = $sr->findOneBy(['id' => $id]);

        return $this->render('sortie/afficher_sortie.html.twig', [
            'sortie' => $sortie
        ]);
    }
}
