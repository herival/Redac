<?php

namespace App\Controller;

use App\Entity\Menage;
use App\Repository\MenageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HKController extends AbstractController
{
    /**
     * @Route("/menage/vaisselle", name="app_vaisselle")
     */
    public function index(MenageRepository $menageRepository): Response
    {
        $derniere_vaisselle = $menageRepository->findBy([],['created_at' => 'DESC'], 5, null);
        // dd($derniere_vaisselle);
        return $this->render('hk/vaisselle.html.twig', [
            'derniere_vaisselle' => $derniere_vaisselle,
        ]);
    }

    /**
     * Creation vaisselle
     * @Route("/menage/do_vaisselle", name="app_do_vaisselle")
     */
    public function app_do_vaisselle(Request $request, EntityManagerInterface $em, MenageRepository $menageRepository): Response
    {
        $oneHourAgo = new \DateTime('-1 hour');
        
        // Vérifier si l'utilisateur a déjà créé une intervention dans la dernière heure
        $recentMenage = $menageRepository->findRecentMenageByUser($oneHourAgo);

        if ($recentMenage) {
            $this->addFlash('danger', 'La vaisselle a été faite!');
            return $this->redirectToRoute('app_vaisselle', [
            ]);
        }
        $vaisselle = new Menage;
        $vaisselle->setLibelle('Vaisselle');
        $vaisselle->setUser($this->getUser()->getPrenom());

        $em->persist($vaisselle);
        $em->flush();

        // Implementer un systeme de controle

        return $this->redirectToRoute('app_vaisselle', [
        ]);
    }

        /**
     * Validation vaisselle
     * @Route("/menage/valid_vaisselle/{id}", name="app_valid_vaisselle")
     */
    public function app_valid_vaisselle($id, MenageRepository $menageRepository, EntityManagerInterface $em): Response
    {

        $vaisselle = $menageRepository->findOneById($id);
        $vaisselle->setValidation(true);
        $vaisselle->setDateValidation(new \DateTime());
        $vaisselle->setValidateur($this->getUser()->getPrenom());

        $em->flush();

        // Implementer un systeme de controle

        return $this->redirectToRoute('app_vaisselle', [
            'controller_name' => 'HKController',
        ]);
    }
}
