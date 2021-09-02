<?php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrialController {
    /**
     * @Route("/client-trial/get", name="client_getter_trial")
     */
    public function clientGetter(ClientRepository $clientRepository, UserRepository $userRepository, AbonnementRepository $abonnementRepository, FactureRepository $factureRepository):Response
    {
        /* $user = $userRepository->find(1);
        dd($user); */

        /* $clients = $clientRepository->find(1);
        dd($clients); */
        
        /* $abonnement = $abonnementRepository->findAll();
        dd($abonnement); */
        
        /* $facture = $factureRepository->find(1);
        dd($facture); */

        return new Response('Hello world');
    }
}