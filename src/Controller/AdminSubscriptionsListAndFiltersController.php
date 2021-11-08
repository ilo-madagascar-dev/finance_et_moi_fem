<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Abonnement;
use App\Form\FormSearchType;
use App\Entity\SubscriptionSearch;
use App\Repository\UserRepository;
use App\Form\SubscriptionSearchType;
use App\Repository\ClientRepository;
use App\Repository\AbonnementRepository;
use App\Repository\SousCompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminSubscriptionsListAndFiltersController extends AbstractController
{
    private $userRepository;

    private $clientRepository;

    public function __construct(UserRepository $userRepository, ClientRepository $clientRepository){
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
    }

    /**
     * @Route("/admin/subscription/list/and/filters", name="admin_subscription_list_and_filters")
     */
    public function index(Request $request): Response
    {
        $search = new SubscriptionSearch();
        $form = $this->createForm(FormSearchType::class, $search);
        $form->handleRequest($request);

        $everyClient = $this->clientRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()){
            $everyClient = $this->clientRepository->findAllClientsResearched($search);
        }

        return $this->render('admin_subscriptions_list_and_filters/index.html.twig', [
            'controller_name' => 'AdminSubscriptionsListAndFiltersController',
            'clients' => $everyClient,
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/admin/sous_compte/list/{id}", name="admin_sous_compte_list")
    */
    public function sousCompte( int $id, SousCompteRepository $sousCompteRepository, ClientRepository $clientrepository ): Response
    {
        $conClient=$clientrepository->findOneBy(['id'=>$id]);
        $consous = $conClient->getSouscomptes()->getValues();
        
        return $this->render('admin_subscriptions_list_and_filters/sous_compte.html.twig',[
            'sous_comptes' => $consous
        ]);
    }

    /**
     * @Route("/des", name="des")
     */
    public function listDesabonne(Request $request, EntityManagerInterface $em, AbonnementRepository $Arep ,ClientRepository $clientrepository ): Response
    {
        $unsubscribedClientsSearch = new SubscriptionSearch();
        $form = $this->createForm(SubscriptionSearchType::class, $unsubscribedClientsSearch);
        $form->handleRequest($request);
        
        $conClients = $clientrepository->findAllUnsubscribedUsers();

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($unsubscribedClientsSearch);
            $conClients = $clientrepository->findUnsubscribedClients($unsubscribedClientsSearch);
            //dd($conClients);
        }
        
        return $this->render('admin_subscriptions_list_and_filters/listDesabonne.html.twig', [
            'clients' => $conClients,
            'form' => $form->createView()
        ]);
    }

}
