<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FemController extends AbstractController
{
    /**
    * @Route("/", name="accueil")
    */
    public function femAccueil(): Response
    {
        return $this->render('accueil/index.html.twig');
    }
    
}
