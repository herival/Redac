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

/**
 * @Route("/admin/facture")
 */
class FactureController extends AbstractController
{
    /**
     * @Route("/client/{client}", name="app_facture")
     */
    public function index($client, 
    InterRepository $interRepository, 
    ParametresRepository $parametresRepository,
    Request $request): Response
    {
        $date_debut = ($parametresRepository->findOneByCle('startdate'))->getValeur();
        $date_fin = ($parametresRepository->findOneByCle('enddate'))->getValeur();
        $mois = ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        
        $periode = (new \DateTime('now'))->format('m');
        $annee = (new DateTime("now"))->format('Y');
        $anne = "2023";
        
        $periode_param = $request->query->get('mois');
        if (isset($periode_param) && $periode_param != null) {
            $periode = $periode_param;
        }
        
        if (isset($periode)) {
            $periode_date_debut = new DateTime($annee . '-' . $periode);
            $periode_date_fin = new DateTime($annee . '-' . $periode);
            $date_debut  = $periode_date_debut->modify('first day of this month');
            $date_fin  = $periode_date_fin->modify('last day of this month');
            
        }
        $facture = $interRepository->countInter($client, $date_debut, $date_fin);
        return $this->render('facture/facture.html.twig', [
            'facture_liste' => $facture,
            'client' => $client,
            "mois_list" => $mois,
            "periode" => $periode, 
        ]);
    }

    /**
     * @Route("/", name="app_facture_all")
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
        //recupérer l'année dans la base de donnée
        $annee = $parametresRepository->findOneByCle('year')->getValeur();
        $annee_charge = $parametresRepository->findOneByCle('year')->getValeur();
        if(!$annee){
            $annee = (new DateTime("now"))->format('Y');
            $annee_charge = (new DateTime("now"))->format('Y');
        }

        $periode_charge = $periode + 1;
        if($periode_charge > 12){
            $periode_charge = $periode_charge - 12;
            $annee_charge = $annee + 1;
        }
        
        
        if (isset($periode)) {
            $periode_date_debut = new DateTime($annee . '-' . $periode);
            $periode_date_fin = new DateTime($annee . '-' . $periode);
            $date_debut  = $periode_date_debut->modify('first day of this month');
            $date_fin  = $periode_date_fin->modify('last day of this month');

            // periode charge 
            $periode_charge_date_debut = new DateTime($annee_charge . '-' . $periode_charge);
            $periode_charge_date_fin = new DateTime($annee_charge . '-' . $periode_charge);
            $date_charge_debut  = $periode_charge_date_debut->modify('first day of this month');
            $date_charge_fin  = $periode_charge_date_fin->modify('last day of this month');
        }
        $facture = $interRepository->countInterAll($date_debut, $date_fin);


        $liste = $interRepository->findByGroupUser($date_charge_debut, $date_charge_fin);
        $total_salaire = 0;
        foreach($liste as $value){
            $total_salaire += $value['salaire'];
        }
        // dd($date_charge_debut, $date_charge_fin);
        return $this->render('facture/facture_all.html.twig', [
            'facture_liste' => $facture,
            "mois_list" => $mois,
            "periode" => $periode, 
            "total_salaire" => $total_salaire, 
            "periode_charge" => $periode_charge, 
            "annee_charge" => $annee_charge, 
            "year" => $annee
        ]);
    }
}
