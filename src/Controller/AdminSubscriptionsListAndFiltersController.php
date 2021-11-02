<?php

namespace App\Controller;

use App\Entity\SubscriptionSearch;
use App\Form\SubscriptionSearchType;
use App\Repository\AbonnementRepository;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
        $form = $this->createForm(SubscriptionSearchType::class, $search);
        $form->handleRequest($request);

        $everyClient = $this->clientRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()){
            //dd($search);
            $everyClient = $this->clientRepository->findAllClientsResearched($search);
        }

        //dd($everyClient);

        return $this->render('admin_subscriptions_list_and_filters/index.html.twig', [
            'controller_name' => 'AdminSubscriptionsListAndFiltersController',
            'clients' => $everyClient,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/search/subscriptions", name="admin_search_subscriptions")
     */
    public function searchEngine(Request $request)
    {
        
    }
}
