<?php

namespace App\Controller;

use DateTime;
use App\Repository\UserRepository;
use App\Repository\InterRepository;
use App\Repository\ParametresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $mois = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        return $this->render('facture/facture.html.twig', [
            'facture_liste' => $facture,
            'client' => $client,
            "mois_list" => $mois,
        ]);
    }

    /**
     * @Route("/home/facture/", name="app_facture_all")
     */
    public function app_facture_all(
        InterRepository $interRepository,
        ParametresRepository $parametresRepository,
        Request $request
    ): Response {

        $date_debut = ($parametresRepository->findOneByCle('startdate'))->getValeur();
        $date_fin = ($parametresRepository->findOneByCle('enddate'))->getValeur();
        $mois = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $periode = (new \DateTime('now'))->format('m');
        
        $periode_param = $request->query->get('mois');
        
        if (isset($periode_param) && $periode_param != null) {
            $periode = $periode_param;
        }
        
        $annee = (new DateTime("now"))->format('Y');
        
        if (isset($periode)) {
            $periode_date_debut = new DateTime($annee . '-' . $periode);
            $periode_date_fin = new DateTime($annee . '-' . $periode);
            $date_debut  = $periode_date_debut->modify('first day of this month');
            $date_fin  = $periode_date_fin->modify('last day of this month');
        }
        $facture = $interRepository->countInterAll($date_debut, $date_fin);
        
        return $this->render('facture/facture_all.html.twig', [
            'facture_liste' => $facture,
            "mois_list" => $mois,
            "periode" => $periode
        ]);
    }
}
