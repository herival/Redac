<?php

namespace App\Controller;

use DateTime;
use App\Entity\Inter;
use App\Form\InterType;
use App\Form\InterFormType;
use App\Repository\UserRepository;
use App\Repository\InterRepository;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterController extends AbstractController
{
    private $requestStack;
    /**
     * Constructor
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    /**
     * @Route("/inter", name="app_inter")
     */
    public function index(): Response
    {
        return $this->render('inter/index.html.twig', [
            'controller_name' => 'InterController',
        ]);
    }

    /**
     * @Route("/home/inter/new", name="app_inter_new")
     */
    public function new_inter(Request $request, EntityManagerInterface $em): Response
    {
        $inter = new Inter;
        $form = $this->createForm(InterFormType::class, $inter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($inter);
            $em->flush();
        }

        return $this->render('inter/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/home/inter", name="inter_jour")
     */
    public function inter_jour(InterRepository $interRepository, EntityManagerInterface $em, Request $request): Response
    {
        $session = $this->requestStack->getSession();
        $date = (new \DateTime('now'))->format('Y-m-d');
        $date_post = $request->query->get('date');

        if ($date_post != null) {
            $date = $date_post;
        }
        $inter_jour = $interRepository->findByDate((new \DateTime($date))->format('Y-m-d'));
        // session pour retour
        $session->set('referer', $request->getUri());

        return $this->render('inter/inter_jour.html.twig', [
            "inter_jour" => $inter_jour,
            "date_url" => $date
        ]);
    }

    /**
     * @Route("/home/inter/edit/{id}", name="edit_inter")
     */
    public function modifier_inter($id, Request $request, EntityManagerInterface $em, InterRepository $interRepository): Response
    {
        // Recuperation sessions
        $session = $this->requestStack->getSession();

        $source_url = ($request->query->get('source') ? $request->query->get('source') : 'app_menu');
        $source_param = $request->query->get('param');
        $backup = $request->query->get('backup');

        $inter = $interRepository->findOneById($id);
        $form = $this->createForm(InterType::class, $inter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($inter);
            $em->flush();

            //redirection
            $referer = $session->get('referer');
            if ($referer == null) {
                return $this->redirectToRoute('app_home');
            }
            return $this->redirect($referer);
        }

        return $this->render('inter/edit.html.twig', [
            'form' => $form->createView(),
            'id_inter' => $id,
            'source' => $source_url,
            'source_param' => $source_param,
            'backup' => $backup,
            'bouton' => 'Modifier'
        ]);
    }


    /**
     * @Route("/home/inter/presence/{id}", name="presence")
     */
    public function presence($id, InterRepository $interRepository, Request $request, EntityManagerInterface $em): Response
    {
        $inter = $interRepository->findOneById($id);
        if ($inter->isPresence() == true) {
            $inter->setPresence(False);
        } else {
            $inter->setPresence(True);
        }

        $em->persist($inter);
        $em->flush();


        return $this->json([
            'code' => 200,
            'message'  => 'etat presence ok',
            200
        ]);
    }
    /**
     * @Route("/home/inter/insertion_mois", name="insertion_inter_mois",  methods={"GET"})
     */
    public function insertion_inter_mois(UserRepository $userRepository, InterRepository $interRepository, EntityManagerInterface $em, ParametresRepository $parametresRepository): Response
    {
        // recuperer toutes les dates entre startDate et $endDate
        function getDatesFromRange($startDate, $endDate)
        {
            $return = array($startDate);
            $start = $startDate;
            $i = 1;
            if (strtotime($startDate) < strtotime($endDate)) {
                while (strtotime($start) < strtotime($endDate)) {
                    $start = date('Y-m-d', strtotime($startDate . '+' . $i . ' days'));
                    $return[] = $start;
                    $i++;
                }
            }

            return $return;
        }

        // recuperer les dates dans les paramètres de la bdd
        $startdate = $parametresRepository->findOneByCle('startdate')->getValeur();
        $enddate = $parametresRepository->findOneByCle('enddate')->getValeur();

        $liste = getDatesFromRange($startdate, $enddate);


        $techs = $userRepository->findByPoste('tech');

        foreach ($liste as $key => $value) {

            $date = (new \DateTime($value));
            // dd($date->format('l'));


            foreach ($techs as $key => $tech) {

                $inter_encours = $interRepository->findByTechAndDate($tech, $date);
                // dd($inter_encours);
                if (!$inter_encours && ($date->format('l')) != 'Sunday') {
                    $inter = new Inter;

                    $inter->setDate($date);
                    $inter->setTechnicien($tech);
                    $inter->setSalaire($tech->getBasesalaire());

                    $em->persist($inter);
                    $em->flush();
                }
            }
        }

        return $this->redirectToRoute('app_menu');
    }

    /**
     * @Route("/home/inter/suppression_mois", name="suppression_inter_mois",  methods={"GET"})
     */
    public function suppression_inter_mois(UserRepository $userRepository, InterRepository $interRepository, EntityManagerInterface $em, ParametresRepository $parametresRepository): Response
    {

        // recuperer les dates dans les paramètres de la bdd
        $startdate = $parametresRepository->findOneByCle('startdate')->getValeur();
        $enddate = $parametresRepository->findOneByCle('enddate')->getValeur();

        $liste = $interRepository->findInterBetweenDate($startdate, $enddate);
        // dd($liste);

        foreach ($liste as $key => $value) {
            $em->remove($value);
            $em->flush();
        }

        return $this->redirectToRoute('app_menu');
    }

    /**
     * @Route("/home/inter/suppression_inter/{id}", name="suppression_inter",  methods={"GET"})
     */
    public function suppression_inter($id, UserRepository $userRepository, InterRepository $interRepository, EntityManagerInterface $em, ParametresRepository $parametresRepository): Response
    {
        $session = $this->requestStack->getSession();
        
        $inter = $interRepository->findOneById($id);
        $em->remove($inter);
        $em->flush();

        //redirection
        $referer = $session->get('referer');
        if ($referer == null) {
            return $this->redirectToRoute('app_home');
        }
        return $this->redirect($referer);
    }
}
