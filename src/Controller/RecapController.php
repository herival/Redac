<?php

namespace App\Controller;

use DateTime;
use App\Repository\InterRepository;
use App\Repository\ParametresRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecapController extends AbstractController
{
    private $requestStack;

    /**
     * EntityManager constructor
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

    }
    /**
     * @Route("/home/recap", name="app_recap")
     */
    public function index(InterRepository $interRepository, ParametresRepository $parametresRepository): Response
    {
        $date_debut = new DateTime(($parametresRepository->findOneByCle('startdate'))->getValeur());
        $date_fin = new DateTime(($parametresRepository->findOneByCle('enddate'))->getValeur());
        $liste = $interRepository->findByGroupUser($date_debut, $date_fin);
        // dd($liste, $date_debut, $date_fin);
        return $this->render('recap/liste.html.twig', [
            'liste' => $liste,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
        ]);
    }

    /**
     * @Route("/home/recap_user/{id}", name="app_recap_user")
     */
    public function app_recap_user($id, InterRepository $interRepository, ParametresRepository $parametresRepository, Request $request): Response
    {
        $session = $this->requestStack->getSession();

        $date_debut = new DateTime(($parametresRepository->findOneByCle('startdate'))->getValeur());
        $date_fin = new DateTime(($parametresRepository->findOneByCle('enddate'))->getValeur());
        
        $periode = $request->query->get('periode');

        if(isset($periode) && $periode != null){
            $periode_date_debut = new DateTime($periode);
            $periode_date_fin = new DateTime($periode);
            $date_debut  = $periode_date_debut->modify('first day of this month');
            $date_fin  = $periode_date_fin->modify('last day of this month');
        }

        $liste = $interRepository->findInterByUser($id, $date_debut, $date_fin);

        // session pour retour
        $session->set('referer', $request->getUri());


        return $this->render('recap/details.html.twig', [
            'liste' => $liste,
            'date_debut' => $date_debut->format('d-m-Y'),
            'date_fin' => $date_fin->format('d-m-Y'),
        ]);
    }

    /**
     * @Route("/home/recap_user_all/{id}", name="app_recap_user_all")
     */
    public function app_recap_user_all($id, InterRepository $interRepository, UserRepository $userRepository, ParametresRepository $parametresRepository, Request $request): Response
    {
        $session = $this->requestStack->getSession();
        $tech = $userRepository->findOneById($id);
        $liste = $interRepository->findCAperYear($id);

        // dd($liste);
        // session pour retour
        $session->set('referer', $request->getUri());


        return $this->render('recap/user.html.twig', [
            'liste' => $liste,
            'tech' => $tech,
        ]);
    }
}
