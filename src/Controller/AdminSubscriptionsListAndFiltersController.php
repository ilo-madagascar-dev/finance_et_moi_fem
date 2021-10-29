<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSubscriptionsListAndFiltersController extends AbstractController
{
    /**
     * @Route("/admin/subscription/list/and/filters", name="admin_subscription_list_and_filters")
     */
    public function index(): Response
    {
        return $this->render('admin_subscriptions_list_and_filters/index.html.twig', [
            'controller_name' => 'AdminSubscriptionsListAndFiltersController',
        ]);
    }
}
