<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Parametres;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompteController extends AbstractController
{
    /**
     * @Route("/home/compte", name="app_compte")
     */
    public function index(UserRepository $userRepository): Response
    {
        $liste = $userRepository->findByposte('tech');
        return $this->render('compte/liste_tech.html.twig', [
            "liste" => $liste
        ]);
    }

    /**
     * @Route("/create_admin2401", name="create_admin")
     */
    public function create_admin(EntityManagerInterface $em): Response
    {
        // $user = new User();

        // $user->setEmail('herimalala.val@gmail.com');
        // $user->setNom('VALISOA');
        // $user->setRoles(["ROLE_ADMIN"]);
        // $user->setPrenom('Herimalala');
        // $user->setStatut(true);
        // $user->setTelephone('0606060606');
        // $user->setPassword('$2y$13$yMuhVodPATiaMt2u/I1KOu5Gwr0V59WfKOuj/ri4EhqRn0dXVtTgq');

        // $em->persist($user);

        $startdate = new Parametres();
        $startdate->setCle('startdate');
        $startdate->setValeur('2023-09-25');
        $enddate = new Parametres();
        $enddate->setCle('enddate');
        $enddate->setValeur('2023-09-25');
        $periode = new Parametres();
        $periode->setCle('periode');
        $periode->setValeur('Septembre');
        $em->persist($startdate);
        $em->persist($enddate);
        $em->persist($periode);
        $em->flush();
        

        return $this->redirectToRoute('app_menu');
    }

    /**
     * @Route("/home/compte/edit/{id}", name="app_compte_edit")
     */
    public function app_compte_edit($id, UserRepository $userRepository, EntityManagerInterface $em, Request $request): Response
    {
        $user = $userRepository->findOneById($id);

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_compte');
        }

        return $this->render('compte/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/home/compte/details/{id}", name="app_compte_details")
     */
    public function app_compte_details($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneById($id);

        return $this->render('compte/details.html.twig', [
            'user' => $user,
        ]);
    }

    
}
