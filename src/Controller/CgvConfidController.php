<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CgvConfidController extends AbstractController
{
    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgv(): Response
    {
        return $this->render('cgv_confid/cgv.html.twig', [
            'controller_name' => 'CgvConfidController',
        ]);
    }

    /**
     * @Route("/mention", name="mention")
     */
    public function confid():Response
    {
        return $this->render('cgv_confid/confid.html.twig', [
            'controller_name' => 'CgvConfidController',
        ]);
    }
}
