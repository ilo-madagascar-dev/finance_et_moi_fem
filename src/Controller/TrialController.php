<?php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrialController {
    /**
     * @Route("/client-trial/get", name="client_getter_trial")
     */
    public function clientGetter(ClientRepository $clientRepository, UserRepository $userRepository, AbonnementRepository $abonnementRepository):Response
    {
        //$user = $userRepository->find(4);
        //dd($user);

        $clients = $clientRepository->findAll();

        dd($clients);
        //$abonnement = $abonnementRepository->findAll();
        //dd($abonnement);
        
        return new Response('Hello world');
    }
}