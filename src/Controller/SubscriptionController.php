<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\SousCompte;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriptionController extends AbstractController
{
    
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("client/subscription/cancel/{id}", name="client_subscription_cancel")
     */
    public function clientSubscriptionCancel(Client $client, Request $request, UserRepository $userRepository): Response
    {
        $userConnect = $this->getUser();

        
        $userInDatabase = $userRepository->findOneBy(['email' => $this->getUser()->getUsername()]);
        
        if (!$userConnect || !$userConnect->getClient()) {
            return $this->redirectToRoute('app_login');
        }

        if ($userConnect->getClient()->getId() != $client->getId()) {
            //dd('different id');
            $this->addFlash('danger', 'Ce n\'est point votre profil');
            return $this->redirectToRoute('dash');
        }

        //dd('same id');

        //dd($request->request->get('_token'));
        if (!$this->isCsrfTokenValid('subscription_cancel'.$client->getId(), $request->request->get('_token'))) {
            //dd('Invalid token');
            return $this->redirectToRoute('app_login');
        }

        //dd('Valid Token');

        /*if ($userConnect->getClient()->getId() !== $client->getId()) {
            $this->addFlash('danger', "Ce compte n'est pas le vôtre !!!!");
            $this->redirectToRoute('login');
        }*/

        $abonnement = $client->getAbonnement();

        \Stripe\Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');

        $subscription = \Stripe\Subscription::retrieve($abonnement->getStripeSubscriptionId());

        $subscription->cancel();
        
        //Désabonnement des sous-compte
        $sousComptesClient = $client->getSousComptes()->getValues();
        $abonnement->setActif(false);
        $userInDatabase->setActive(false);
        $this->em->persist($userInDatabase);

        $this->em->flush();
        
        /*foreach ($sousComptesClient as $uniqueSousCompteClient) {
            $sousComptesClientAbonnement = $uniqueSousCompteClient->getAbonnement();
            
            if ($uniqueSousCompteClient->getUser()->getActive() !== false) {
                \Stripe\Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');

                $sousCompteSubscription = \Stripe\Subscription::retrieve($sousComptesClientAbonnement->getStripeSubscriptionId());
                $sousCompteSubscription->cancel();
                $uniqueSousCompteClient->getUser()->setActive(false);
            }
            $this->em->persist($sousComptesClientAbonnement);
            $this->em->persist($uniqueSousCompteClient);
            $this->em->flush();
        }*/
        
        //dd($subscription);

        return $this->render('subscription/client_cancel.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }

    /**
     * @Route("affiliate/subscription/cancel/{id}", name="affiliate_subscription_cancel")
     */
    public function affiliateSubscriptionCancel(SousCompte $sousCompte, Request $request, UserRepository $userRepository): Response
    {
        $userConnect = $this->getUser();

        
        $userInDatabase = $userRepository->findOneBy(['email' => $sousCompte->getUser()->getUsername()]);
        
        if (!$userConnect || !$userConnect->getClient()) {
            return $this->redirectToRoute('app_login');
        }

        $usersSousComptes = $userConnect->getClient()->getSousComptes()->getValues();
        $sousCompteIds = [];

        foreach ($usersSousComptes as $uniqueSousCompte) {
            $sousCompteIds[] = $uniqueSousCompte->getId();
        }

        //dd($sousCompte->getId(), $sousCompteIds);

        if (!in_array($sousCompte->getId(), $sousCompteIds)) {
            //dd('Not user\'s sous-compte !!!!');
            $this->addFlash('danger', 'Ce n\'est point votre profil');
            return $this->redirectToRoute('dash');
        }

        //dd("User's sous-compte");

        //dd('same id');

        //dd($request->request->get('_token'));
        if (!$this->isCsrfTokenValid('subscription_cancel'.$sousCompte->getId(), $request->request->get('_token'))) {
            //dd('Invalid token');
            return $this->redirectToRoute('app_login');
        }
        $abonnement = $sousCompte->getAbonnement();

        \Stripe\Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');

        $subscription = \Stripe\Subscription::retrieve($abonnement->getStripeSubscriptionId());

        //dd($subscription);

        $subscription->cancel();
            
        $abonnement->setActif(false);
        $userInDatabase->setActive(false);
        $this->em->persist($userInDatabase);

        $this->em->flush();
            
        $this->addFlash('success',"Le désabonnement du sous-compte a bien été réalisé !!!!");
        
        return $this->redirectToRoute('slist');
        /** return $this->render('subscription/client_cancel.html.twig', [
         *   'controller_name' => 'SubscriptionController',
         * ]); 
         **/
    }

    /**
     * @Route("client/subscription/cancel/period/end/{id}", name="client_subscription_cancel_at_period_end")
     */
    public function clientSubscriptionCancelAtPeriodEnd(Client $client, Request $request): Response
    {
        $userConnect = $this->getUser();

        if (!$userConnect || !$userConnect->getClient()) {
            $this->redirectToRoute('login');
        }

        /*if ($userConnect->getClient()->getId() !== $client->getId()) {
            $this->addFlash('danger', "Ce compte n'est pas le vôtre !!!!");
            $this->redirectToRoute('login');
        }*/

        $abonnement = $client->getAbonnement();

        \Stripe\Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');

        //$subscription = \Stripe\Subscription::retrieve($abonnement->getStripeSubscriptionId());
        
        \Stripe\Subscription::update(
            $abonnement->getStripeSubscriptionId(),
            [
              'cancel_at_period_end' => true,
            ]
        );
        
        $abonnement->setActif(false);
        $this->em->flush();

        return $this->render('subscription/client_cancel.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }

    /**
     * @Route("client/subscription/reactivate/period/end/{id}", name="client_subscription_reactivate_before_period_end")
     */
    public function clientReactivateBeforePeriodEnd(Client $client, Request $request): Response
    {
        $userConnect = $this->getUser();

        if (!$userConnect || !$userConnect->getClient()) {
            $this->redirectToRoute('login');
        }

        /*if ($userConnect->getClient()->getId() !== $client->getId()) {
            $this->addFlash('danger', "Ce compte n'est pas le vôtre !!!!");
            $this->redirectToRoute('login');
        }*/

        $abonnement = $client->getAbonnement();

        \Stripe\Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');

        //$subscription = \Stripe\Subscription::retrieve($abonnement->getStripeSubscriptionId());
        
        $subscription = \Stripe\Subscription::retrieve($abonnement->getStripeSubscriptionId());
        \Stripe\Subscription::update($abonnement->getStripeSubscriptionId(), [
        'cancel_at_period_end' => false,
        'proration_behavior' => 'create_prorations',
        'items' => [
            [
            'id' => $subscription->items->data[0]->id,
            'price' => $client->getAbonnement()->getTypeAbonnement()->getPriceID(),
            ],
        ],
        ]);
        
        $abonnement->setActif(false);
        $this->em->flush();

        return $this->render('subscription/client_subscription_reactivation.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }

    /**
     * @Route("sous/compte/subscription/cancel/{id}", name="sous_compte_subscription_cancel")
     */
    public function sousCompteSubscriptionCancel(SousCompte $sousCompte, Request $request): Response
    {
        $userConnect = $this->getUser();

        if (!$userConnect || !$userConnect->getSouscompte()) {
            $this->redirectToRoute('login');
        }

        $abonnement = $sousCompte->getAbonnement();

        \Stripe\Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');

        $subscription = \Stripe\Subscription::retrieve($abonnement->getStripeSubscriptionId());
        $subscription->cancel();
        
        $abonnement->setActif(false);
        $this->em->flush();

        return $this->render('subscription/client_cancel.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }


    /* \Stripe\Subscription::update(
        $abonnement->getStripeSubscriptionId(),
        [
          'cancel_at_period_end' => true,
        ]
    ); */
    /**
     * @Route("sous/compte/subscription/cancel/period/end/{id}", name="sous_compte_subscription_cancel_period_end")
     */
    public function sousCompteSubscriptionCancelPeriodEnd(SousCompte $sousCompte, Request $request): Response
    {
        $userConnect = $this->getUser();

        if (!$userConnect || !$userConnect->getSouscompte()) {
            $this->redirectToRoute('login');
        }

        $abonnement = $sousCompte->getAbonnement();

        \Stripe\Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');

        \Stripe\Subscription::update(
            $abonnement->getStripeSubscriptionId(),
            [
              'cancel_at_period_end' => true,
            ]
        );
        
        $abonnement->setActif(false);
        $this->em->flush();

        return $this->render('subscription/client_cancel.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }
}
