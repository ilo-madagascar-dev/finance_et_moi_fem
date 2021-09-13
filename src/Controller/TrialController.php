<?php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use App\Repository\PaiementRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrialController extends AbstractController {
    /**
     * @Route("/client-trial/get", name="client_getter_trial")
     */
    public function clientGetter(PaiementRepository $paiementRepository, ClientRepository $clientRepository, UserRepository $userRepository, AbonnementRepository $abonnementRepository, FactureRepository $factureRepository):Response
    {
        //$user = $userRepository->find(1);
        //dd($user); */

        $userClients = $this->getUser()->getClient()->getSouscomptes()->getValues();
        //dd($userClients);

        //$clients = $clientRepository->find(2);
        //dd($clients);
        
        //$abonnement = $abonnementRepository->findAll();
        //dd($abonnement);
        
        $facture = $factureRepository->find(3);
        dd($facture->getPaiements()->getValues());
        //dd($facture->getPaiements()->getKeys());
        /* $paiement = $paiementRepository->find(1);
        dd($paiement); */
        
        return new Response('Hello world');
    }
}