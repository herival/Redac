<?php

namespace App\Controller;

use DateTime;
use App\Repository\InterRepository;
use App\Repository\ParametresRepository;
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
        return $this->render('recap/liste.html.twig', [
            'liste' => $liste,
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
        $liste = $interRepository->findInterByUser($id, $date_debut, $date_fin);

        // session pour retour
        $session->set('referer', $request->getUri());

        return $this->render('recap/details.html.twig', [
            'liste' => $liste,
        ]);
    }
}
