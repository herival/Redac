<?php

namespace App\Controller;

use App\Form\ChangeDateType;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/setting")
 */
class SettingController extends AbstractController
{
    /**
     * @Route("/", name="app_setting")
     */
    public function index(): Response
    {
        return $this->render('setting/index.html.twig', [
            'controller_name' => 'SettingController',
        ]);
    }

     /**
     * @Route("/change_periode", name="change_periode")
     */
    public function change_periode(ParametresRepository $parametresRepository, Request $request, EntityManagerInterface $em): Response
    {
        // recuperer la source 
        $source_url = ($request->query->get('source')?$request->query->get('source'):'app_menu');

        // recuperer le parametre date demarrage
        $date_setting = $parametresRepository->findOneByCle('periode');
        $date_debut = $parametresRepository->findOneByCle('startdate');
        $date_fin = $parametresRepository->findOneByCle('enddate');

        if(!$date_setting){
            return $this->redirectToRoute('app_menu');
        }
        $form = $this->createForm(ChangeDateType::class, $date_setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $periode = $request->request->get('change_date');

            switch ($periode['valeur']){
                case 'Janvier' : $date_debut->setValeur('2023-01-01'); $date_fin->setValeur('2023-01-31'); break;
                case 'Fevrier' : $date_debut->setValeur('2023-02-01'); $date_fin->setValeur('2023-02-28'); break;
                case 'Mars' : $date_debut->setValeur('2023-03-01'); $date_fin->setValeur('2023-03-31'); break;
                case 'Avril' : $date_debut->setValeur('2023-04-01'); $date_fin->setValeur('2023-04-30'); break;
                case 'Mai' : $date_debut->setValeur('2023-05-01'); $date_fin->setValeur('2023-05-31'); break;
                case 'Juin' : $date_debut->setValeur('2023-06-01'); $date_fin->setValeur('2023-06-30'); break;
                case 'Juillet' : $date_debut->setValeur('2023-07-01'); $date_fin->setValeur('2023-07-31'); break;
                case 'Aout' : $date_debut->setValeur('2023-08-01'); $date_fin->setValeur('2023-08-31'); break;
                case 'Septembre' : $date_debut->setValeur('2023-09-01'); $date_fin->setValeur('2023-09-30'); break;
                case 'Octobre' : $date_debut->setValeur('2023-10-01'); $date_fin->setValeur('2023-10-31'); break;
                case 'Novembre' : $date_debut->setValeur('2023-11-01'); $date_fin->setValeur('2023-11-30'); break;
                case 'Decembre' : $date_debut->setValeur('2023-12-01'); $date_fin->setValeur('2023-12-31'); break;
            }
            $em->flush();
            
            
            return $this->redirectToRoute($source_url);
        }
        
        return $this->render('setting/periode.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Changer Période'
        ]);
    }
}
