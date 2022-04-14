<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="affichage")
     */
    public function affichage(LieuRepository $lieuRepository): Response
    {

        return $this->render('home/home.html.twig');
    }
}
