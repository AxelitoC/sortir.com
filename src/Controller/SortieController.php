<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="new_sortie")
     */
    public function create(): Response
    {
        return $this->render('sortie/new_sortie.html.twig', [

        ]);
    }
}
