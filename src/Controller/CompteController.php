<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Parametres;
use App\Repository\UserRepository;
use App\Repository\ParametresRepository;
use Doctrine\ORM\EntityManagerInterface;
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
}
