<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Stripe\Stripe;

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
            $session->set('possibleNewUser', $newClient);
            //dd($session->get('possibleNewUser'));

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
    public function registrationPaymentSuccess(Request $request, SessionInterface $session):Response
    {
        $session_id = $request->get('session_id');

        Stripe::setApiKey($_ENV['STRIPE_SECRET']);

        $stripe_session = \Stripe\Checkout\Session::retrieve(
          $session_id,
          []
        );

        //Création d'un nouvel abonnement
        $nouvelAbonnementPotentiel = new Abonnement();

        dd($stripe_session);

        $nouvelAbonnementPotentiel->setStripeSubscriptionId($stripe_session->subscription);
        $nouvelAbonnementPotentiel->setStripeCusId($stripe_session->customer);


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
