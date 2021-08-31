<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\User;
use App\Form\ClientType;
use App\Service\ApiService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Stripe\Stripe;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request, SessionInterface $session): Response
    {
        $newClient = new Client();
        
        $form = $this->createForm(ClientType::class, $newClient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Les informations de la première étape seront enregistrées dans la premimère étape et seront flushées si l'utiliseur valide son abonnement et qu'il obtient un vd
            //Le User relatif à ce client ne sera créé que lorsque les deux dernières étapes (càd le paiement et la création d'un compte sur Lenbox seront validées) 
            //dd($newClient);
            $session->set('possibleNewUser', $newClient);

            return $this->redirectToRoute('registration_second_step');
        }

        return $this->render('registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/registration/second-step", name="registration_second_step")
     */
    public function registrationSeconStep(SessionInterface $session)
    {
        //dd($session->get('possibleNewUser'));
        return $this->render('registration/secondStepRegistration_trial.html.twig');
    }

    /**
     * @Route("/registration/payment", name="registration_payment") 
     */
    public function registrationPayment():Response
    {
        //Stripe::setApiKey('sk_test_51JSbdPBW8SyIFHAgGLf2rFeDFKCcS0UfKFRuGifDaCKnQg9t1m6PSK1NxwSuf23JcmY5HK8ZTcV0Pvaex4E2RaIt00fbf8PcYC');
        Stripe::setApiKey($_ENV['STRIPE_SECRET']);
        $priceId = 'price_1JT0YJBW8SyIFHAgmEuizs6Z';
        
          $paymentSession = \Stripe\Checkout\Session::create([
            'success_url' => 'http://localhost:8000/registration/payment/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->generateUrl('registration_payment_failed', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'line_items' => [[
              'price' => $priceId,
              // For metered billing, do not pass quantity
              'quantity' => 1,
            ]],
          ]);
        //dd($session); //Sauvegarder le payement intent de l'utilisateur dans la base de données afin d'avoir une référence quant à son paiement

        //return $response->withHeader('Location', $session->url)->withStatus(303);;

        return $this->redirect($paymentSession->url, 303);
    }

    /**
     * @Route("/registration/payment/success", name="registration_payment_success")
     */
    public function registrationPaymentSuccess(Request $request, SessionInterface $session, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, ApiService $apiService):Response
    {
        $session_id = $request->get('session_id');

        Stripe::setApiKey($_ENV['STRIPE_SECRET']);

        $stripe_session = \Stripe\Checkout\Session::retrieve(
          $session_id,
          []
        );

        $potentialClient = $session->get('possibleNewUser');
        //Création d'un nouvel abonnement
        $nouvelAbonnementPotentiel = new Abonnement();
        
        $nouvelAbonnementPotentiel->setStripeSubscriptionId($stripe_session->subscription);
        $nouvelAbonnementPotentiel->setStripeCusId($stripe_session->customer);
        $nouvelAbonnementPotentiel->setMode($stripe_session->mode);
        $nouvelAbonnementPotentiel->setStatutPaiement($stripe_session->payment_status);
        $nouvelAbonnementPotentiel->setDateDebutAbonnement(new DateTime());
        $nouvelAbonnementPotentiel->setClient($potentialClient);
        
        $session->set('abonnementPotentiel', $nouvelAbonnementPotentiel);
        //dd($stripe_session->amount_total/100);
        
        //Création de la facture potentielle relative à l'abonneement
        $nouvelleFacturePotentielle = new Facture;

        $statutFacture = $stripe_session->payment_status === "paid" ? true : false;

        $nouvelleFacturePotentielle->setDateEmissionFacture(new DateTime());
        $nouvelleFacturePotentielle->setFactureAcquitee($statutFacture);
        $nouvelleFacturePotentielle->setMontantTtcFacture($stripe_session->amount_total/100);
        $nouvelleFacturePotentielle->setAbonnement($nouvelAbonnementPotentiel);
        $nouvelleFacturePotentielle->setPourcentageTva(20);
        
        //dd($stripe_session);
        $session->set('facturePotentielle', $nouvelleFacturePotentielle);
        
        //dd($session->get('possibleNewUser'), $session->get('abonnementPotentiel'), $session->get('facturePotentielle'));
        
       
        //Création de l'entité user relatif au client
        $userRelatedToPotentialClient = new User;
        
        //Password encrypting
        $encryptedPassword = $passwordEncoder->encodePassword($userRelatedToPotentialClient, $potentialClient->getPassword());

        $userRelatedToPotentialClient->setEmail($potentialClient->getEmail());
        $userRelatedToPotentialClient->setPassword($encryptedPassword);
        $userRelatedToPotentialClient->setDateCreationUtilisateur(new DateTime());
        $userRelatedToPotentialClient->setActive(true);
        $userRelatedToPotentialClient->setRoles(["ROLE_CLIENT"]);

        $uniqId = md5(uniqid());
        $clientsInfosFromLenbox = $apiService->postLenbox($potentialClient->getNomEntreprise(), $potentialClient->getEmail(), $potentialClient->getTelMobile(), $uniqId);
        
        //dd($clientsInfosFromLenbox['response']['vd']);
        $clientsVd = $clientsInfosFromLenbox['response']['vd'];

        //Données client à enregistrer
        $potentialClient->setUniqid($uniqId);
        $potentialClient->setUniqid($uniqId);
        $potentialClient->setVd($clientsVd);
        $potentialClient->setStripeToken(md5(uniqid()));
        $potentialClient->setPassword($encryptedPassword);
        $potentialClient->setUser($userRelatedToPotentialClient);
        
        //$potentialSubscriber = $session->get('abonnementPotentiel');
        $facturePotentielle = $session->get('facturePotentielle');

        $em->persist($potentialClient);
        $em->persist($facturePotentielle);
        $em->flush();

        return $this->render('registration/successPayment.html.twig');
    }

    /**
     * @Route("/registration/payment/failed", name="registration_payment_failed")
     */
    public function registrationPaymentFailed():Response
    {
        //paymentFailure.html.twig
        return $this->render('registration/paymentFailure.html.twig');
    }
}
