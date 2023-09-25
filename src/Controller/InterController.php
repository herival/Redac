<?php

namespace App\Controller;

use App\Entity\Inter;
use App\Form\InterFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\InterRepository;
use DateTime;

class InterController extends AbstractController
{
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
     * @Route("/home/inter/new", name="app_inter")
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
     * @Route("/home/inter/inter_du_jour/{date?}", name="inter_jour")
     */
    public function inter_jour($date, UserRepository $userRepository, InterRepository $interRepository, EntityManagerInterface $em): Response
    {

        $inter_jour = $interRepository->findByDate((new \DateTime($date))->format('Y-m-d'));

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
        $source_url = ($request->query->get('source')?$request->query->get('source'):'menu');
        $source_param = $request->query->get('param');
        $backup = $request->query->get('backup');

        $inter = $interRepository->findOneById($id); 
        $form = $this->createForm(InterType::class, $inter);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($inter);
            $em->flush(); 

            return $this->redirectToRoute($source_url, [
                'date' => $source_param,
                'id'=> $source_param,
                'backup' => $backup,
                
            ]);
        }

        return $this->render('inter/index.html.twig', [
            'form' => $form->createView(), 
            'id_inter' => $id, 
            'source' => $source_url,
            'source_param' => $source_param,
            'backup' => $backup
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
        }else{
            $inter->setPresence(True);
        }

        $em->persist($inter);
        $em->flush(); 


        return $this->json([
            'code'=>200,
            'message'  => 'etat presence ok', 
            200 
        ]);
    }
}
