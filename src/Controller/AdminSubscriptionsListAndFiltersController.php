<?php

namespace App\Controller;

use App\Entity\SubscriptionSearch;
use App\Form\SubscriptionSearchType;
use App\Repository\AbonnementRepository;
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
    /**
     * @Route("/admin/subscription/list/and/filters", name="admin_subscription_list_and_filters")
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $search = new SubscriptionSearch();
        $form = $this->createForm(SubscriptionSearchType::class, $search);
        $form->handleRequest($request);

        $everyUser = $userRepository->findAll();

        return $this->render('admin_subscriptions_list_and_filters/index.html.twig', [
            'controller_name' => 'AdminSubscriptionsListAndFiltersController',
            'abonnements' => $everyUser,
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
