<?php

namespace App\Controller;

use mysqli;
use MySQLDump;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DbController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    
    /**
     * @Route("/admin/db/export/98u325_sav@tkl56138", name="app_db_export")
     */
    public function app_db_export(): Response
    {
        $link = $this->getParameter('DB_HOST');
        // //vider le dossier 
        $dir_name = "";  // nom du répertoire
        array_map('unlink', glob($dir_name . '*.sql.gz'));

        $serveur = $this->getParameter('DB_HOST');
        $db_name = $this->getParameter('DB_NAME');
        $user = $this->getParameter('DB_USER');
        $password = $this->getParameter('DB_PASSWORD');

        $db = new mysqli($serveur, $user, $password, $db_name);
        $dump = new MySQLDump($db);
        $date = new \DateTime('now');

        $file_name = 'sltek_' . ($date->format('YmdHis')) . '.sql.gz';
        $dump->save($file_name);

        //Recuperer le dossier public
        $publicDirectory_string = $this->params->get('kernel.project_dir');
        $publicDirectory = str_replace('\\', '/', $publicDirectory_string);

        return $this->redirect('/' . $file_name);
    }
    /**
     * @Route("/admin/db/admin", name="app_db_admin")
     */
    public function app_db_admin(): Response
    {
        return $this->render('db/index.html.twig', [

        ]);
    }
}
