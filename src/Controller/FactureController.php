<?php

namespace App\Controller;

use App\Repository\InterRepository;
use App\Repository\ParametresRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    /**
     * @Route("/home/facture/{client}", name="app_facture")
     */
    public function index($client, InterRepository $interRepository, ParametresRepository $parametresRepository, UserRepository $userRepository): Response
    {
        $date_debut = ($parametresRepository->findOneByCle('startdate'))->getValeur();
        $date_fin = ($parametresRepository->findOneByCle('enddate'))->getValeur();
        $facture = $interRepository->countInter($client, $date_debut, $date_fin);

        return $this->render('facture/facture.html.twig', [
            'facture_liste' => $facture,
            'client' => $client
        ]);
    }
}
