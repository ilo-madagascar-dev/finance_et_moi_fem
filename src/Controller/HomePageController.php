<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index(): Response
    {
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }
    
    /**
     * @Route("/home", name="home_mqbaka")
     */
    public function mqbaka_home(): Response
    {
        return $this->render('mqbaka_home/index.html.twig', [
            'controller_name' => 'MqbakaHomeController',
        ]);
    }
    
    /**
     * @Route("/credit_conso", name="credit_conso")
     */
    public function credit_conso(): Response
    {
        return $this->render('mqbaka_home/credit_conso.html.twig', [
            'controller_name' => 'MqbakaHomeController',
        ]);
    }
}
