<?php

namespace App\Controller;

use App\Form\FilterFormType;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="affichage")
     * @param LieuRepository $lieuRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SortieRepository $repository
     * @return Response
     */
    public function affichage(LieuRepository $lieuRepository, Request $request, EntityManagerInterface $entityManager, SortieRepository $repository): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(FilterFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $search = $repository->filter($request->request->get('filter_form'), $this->getUser()->getId());
        }

        $sortie=$repository->findAllDate();

        return $this->render('home/home.html.twig', [
            'form' => $form->createView(),
            'sortie'=>$sortie
        ]);
    }

}
