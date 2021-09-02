<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrialController {
    /**
     * @Route("/client-trial/get", name="client_getter_trial")
     */
    public function clientGetter(ClientRepository $clientRepository, UserRepository $userRepository):Response
    {
        //$user = $userRepository->find(4);
        //dd($user);

        $clients = $clientRepository->find(4);

        dd($clients);

        return new Response('Hello world');
    }
}