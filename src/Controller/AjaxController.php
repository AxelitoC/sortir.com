<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{

    /**
     * @Route("/ajax", name="ajax_")
     * @param LieuRepository $lieuRepository
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function rechercheLieuByVille(LieuRepository $lieuRepository, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        $json_data = [];
        $i = 0;

            $lieux = $lieuRepository->findBy(['ville' => $request->request->get('ville_id')]);

            if (sizeof($lieux)>0){
                foreach ($lieux as $lieu){
                    $json_data [$i++] = ['id' =>$lieu->getId(), 'nom'=>$lieu->getNom(), 'rue' =>$lieu->getRue(), 'codePostale'=>$lieu->getVille()->getCodePostal()];
                }
                return new JsonResponse($json_data);
            }else{
                $json_data [$i++] = ['id' => '', 'nom' => 'Pas de lieu pour cette destination'];

                return new JsonResponse($json_data);
            }
    }

}
