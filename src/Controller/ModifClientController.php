<?php

namespace App\Controller;

use App\Entity\Client;
use App\Service\ApiService;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModifClientController extends AbstractController
{
    /**
     * @Route("/agence/modif/{id}", name="modif_agence")
     */
    public function modifieAgence(Client $clients, Request $request, EntityManagerInterface $em, ApiService $apiService, UserRepository $userRepository, ClientRepository $clientRep)
    {
        // dd($this->getUser()->getClient());

        $abonnement = $this->getUser()->getClient()->getAbonnement()->getStripeSubscriptionId(); 
        $present_mail = $clients->getEmail();

        $form = $this->createForm(ClientModifType::class, $clients);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # code...
            $userRelatedToPotentialClient = $clients->getUser();
            //dd($userRelatedToPotentialClient);
            $userConnectedVd = $clients->getVd();
            $uniqid = $clients->getUniqid();

            if( $clients->getEmail() !== $present_mail )
            {
                $userExistence = $userRepository->findBy(['email' => $clients->getEmail()]);
            
                if ($userExistence) {

                    //dd("loza !!!");

                    $this->addFlash('danger', 'Cet e-mail est déjà relié à un utilisateur');

                    return $this->redirectToRoute('modif_agence');
                }

            }
            
            try{
                $clientsInfosFromLenbox = $apiService->postLenbox($clients->getNomEntreprise(), $clients->getEmail(), $clients->getTelMobile(), $uniqid, true);
            }
            catch (Exception $e){
                dd('Exception reçue : ', $e->getMessage() );
            }

            $userRelatedToPotentialClient->setEmail($clients->getEmail());

            $em->persist($clients);
            $em->flush();
        }

        return $this->render('client/index.html.twig', [
            'client' => $this->getUser()->getClient(),
            'form' => $form->createView(),
            'role' => $this->getUser()->getRoles()[0],
            'type' => $abonnement,
        ]);
    }
}
