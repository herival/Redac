<?php

namespace App\Controller;

use App\Repository\InterRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecapController extends AbstractController
{
    /**
     * @Route("/home/recap", name="app_recap")
     */
    public function index(InterRepository $interRepository): Response
    {
        $date_debut = new DateTime('2023-09-01');
        $date_fin = new DateTime('2023-09-30');
        $liste = $interRepository->findByGroupUser($date_debut, $date_fin);
        return $this->render('recap/liste.html.twig', [
            'liste' => $liste,
        ]);
    }
}
