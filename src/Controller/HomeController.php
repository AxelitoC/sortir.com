<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="affichage")
     */
    public function affichage(LieuRepository $lieuRepository, Request $request, EntityManagerInterface $entityManager, SortieRepository $repository): Response
    {
        $sortie=$repository->findBy(['online'=>true]);

        return $this->render('home/home.html.twig', [
            'sortie'=>$sortie
        ]);
    }


}
