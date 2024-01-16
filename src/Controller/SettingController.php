<?php

namespace App\Controller;

use DateTime;
use App\Entity\Parametres;
use App\Form\ChangeDateType;
use App\Form\ChangeYearType;
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
        $source_url = ($request->query->get('source') ? $request->query->get('source') : 'app_menu');

        // recuperer le parametre date demarrage
        $date_setting = $parametresRepository->findOneByCle('periode');
        $date_debut = $parametresRepository->findOneByCle('startdate');
        $date_fin = $parametresRepository->findOneByCle('enddate');
        $year = $parametresRepository->findOneByCle('year')->getValeur();

        if (!$date_setting) {
            return $this->redirectToRoute('app_menu');
        }
        $form = $this->createForm(ChangeDateType::class, $date_setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $periode = $request->request->get('change_date');

            switch ($periode['valeur']) {
                case 'Janvier':
                    $date_debut->setValeur($year.'-01-01');
                    $date_fin->setValeur($year.'-01-31');
                    break;
                case 'Fevrier':
                    $date_debut->setValeur($year.'-02-01');
                    $date_fin->setValeur($year.'-02-28');
                    break;
                case 'Mars':
                    $date_debut->setValeur($year.'-03-01');
                    $date_fin->setValeur($year.'-03-31');
                    break;
                case 'Avril':
                    $date_debut->setValeur($year.'-04-01');
                    $date_fin->setValeur($year.'-04-30');
                    break;
                case 'Mai':
                    $date_debut->setValeur($year.'-05-01');
                    $date_fin->setValeur($year.'-05-31');
                    break;
                case 'Juin':
                    $date_debut->setValeur($year.'-06-01');
                    $date_fin->setValeur($year.'-06-30');
                    break;
                case 'Juillet':
                    $date_debut->setValeur($year.'-07-01');
                    $date_fin->setValeur($year.'-07-31');
                    break;
                case 'Aout':
                    $date_debut->setValeur($year.'-08-01');
                    $date_fin->setValeur($year.'-08-31');
                    break;
                case 'Septembre':
                    $date_debut->setValeur($year.'-09-01');
                    $date_fin->setValeur($year.'-09-30');
                    break;
                case 'Octobre':
                    $date_debut->setValeur($year.'-10-01');
                    $date_fin->setValeur($year.'-10-31');
                    break;
                case 'Novembre':
                    $date_debut->setValeur($year.'-11-01');
                    $date_fin->setValeur($year.'-11-30');
                    break;
                case 'Decembre':
                    $date_debut->setValeur($year.'-12-01');
                    $date_fin->setValeur($year.'-12-31');
                    break;
            }
            $em->flush();


            return $this->redirectToRoute($source_url);
        }

        return $this->render('setting/periode.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Changer Période'
        ]);
    }
    /**
     * @Route("/change_year", name="change_year")
     */
    public function change_year(ParametresRepository $parametresRepository, Request $request, EntityManagerInterface $em): Response
    {
        // recuperer la source 
        $source_url = ($request->query->get('source') ? $request->query->get('source') : 'app_menu');

        // recuperer le parametre date demarrage
        $year = $parametresRepository->findOneByCle('year');

        if (!$year) {
            $current_year = new Parametres;
            $current_year->setCle('year');
            $current_year->setValeur((new DateTime("now"))->format('Y'));
            $em->persist($current_year);
            $em->flush();
            $year = $parametresRepository->findOneByCle('year');
        }

        $form = $this->createForm(ChangeYearType::class, $year);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute($source_url);
        }

        return $this->render('setting/periode.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Changer Année'
        ]);
    }
}
