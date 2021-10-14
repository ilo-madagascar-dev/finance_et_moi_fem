<?php

namespace App\Controller;

use DateTime;
use DateTimeImmutable;
use App\Entity\Facture;
use App\Entity\Paiement;
use App\Entity\StripeEvent;
use App\Repository\AbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WebHookController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;    
    }

    /**
     * @Route("/webhook", name="web_hook")
     */
    public function index(Request $request): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');
        //$payload = @file_get_contents('php://input');
        $endpoint_secret = 'whsec_xayjuDO33MODkpYjQhw53ntA4OggyslR';

        $payload = $request->getContent();
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
            json_decode($payload, true),  $sig_header, $endpoint_secret
        );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        $invoice = [];
        // Handle the event
        switch ($event->type) {
        case 'invoice.created':
            $invoice = $event->data->object;
        case 'invoice.deleted':
            $invoice = $event->data->object;
        case 'invoice.finalization_failed':
            $invoice = $event->data->object;
        case 'invoice.finalized':
            $invoice = $event->data->object;
        case 'invoice.marked_uncollectible':
            $invoice = $event->data->object;
        case 'invoice.paid':
            $invoice = $event->data->object;
        case 'invoice.payment_action_required':
            $invoice = $event->data->object;
        case 'invoice.payment_failed':
            $invoice = $event->data->object;
        case 'invoice.payment_succeeded':
            $invoice = $event->data->object;
        case 'invoice.sent':
            $invoice = $event->data->object;
        case 'invoice.upcoming':
            $invoice = $event->data->object;
        case 'invoice.updated':
            $invoice = $event->data->object;
        case 'invoice.voided':
            $invoice = $event->data->object;
        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
        
        $data =  ['invoice' => $invoice];
        $response = $this->json($data, 200, [], ['groups'=>'conversation:read']);

        return $response;
        /* return $this->render('web_hook/index.html.twig', [
            'controller_name' => 'WebHookController',
        ]); */
    }

    /**
     * @Route("/webhook/test/deux", name="web_hook_test_deux")
     */
    public function webhookTestDeux(Request $request): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_VePHdqKTYQjKNInc7u56JBrQ');
        //$payload = @file_get_contents('php://input');
        $payload = $request->getContent();
        
        $event = null;
        try {
            $event = \Stripe\Event::constructFrom(
            json_decode($payload, true)
        );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
        case 'invoice.created':
            $invoice = $event->data->object;
        case 'invoice.deleted':
            $invoice = $event->data->object;
        case 'invoice.finalization_failed':
            $invoice = $event->data->object;
        case 'invoice.finalized':
            $invoice = $event->data->object;
        case 'invoice.marked_uncollectible':
            $invoice = $event->data->object;
        case 'invoice.paid':
            $invoice = $event->data->object;
        case 'invoice.payment_action_required':
            $invoice = $event->data->object;
        case 'invoice.payment_failed':
            $invoice = $event->data->object;
        case 'invoice.payment_succeeded':
            $invoice = $event->data->object;
        case 'invoice.sent':
            $invoice = $event->data->object;
        case 'invoice.upcoming':
            $invoice = $event->data->object;
        case 'invoice.updated':
            $invoice = $event->data->object;
        case 'invoice.voided':
            $invoice = $event->data->object;
        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
        
        $data =  ['invoice' => $invoice];
        $response = $this->json($data, 200, [], ['groups'=>'conversation:read']);

        return $response;
        /* return $this->render('web_hook/index.html.twig', [
            'controller_name' => 'WebHookController',
        ]); */
    }

    /**
     * @Route("/henintsoa/account/webhook", name="henintsoa_account_web_hook", methods={"POST"})
     */
    public function henintsoaAccountWebHook(Request $request, AbonnementRepository $abonnementRepository): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_51JSbdPBW8SyIFHAgGLf2rFeDFKCcS0UfKFRuGifDaCKnQg9t1m6PSK1NxwSuf23JcmY5HK8ZTcV0Pvaex4E2RaIt00fbf8PcYC');
        //$payload = @file_get_contents('php://input');
        $payload = $request->getContent();
        $payloadDecoded = json_decode($payload);

        //dd($payloadDecoded->data->object->customer);
        //dd($payloadDecoded->data->object->subscription);

        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
            json_decode($payload, true)
        );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
        case 'invoice.created':
            $invoice = $event->data->object;
        case 'invoice.deleted':
            $invoice = $event->data->object;
        case 'invoice.finalization_failed':
            $invoice = $event->data->object;
        case 'invoice.finalized':
            $invoice = $event->data->object;
        case 'invoice.marked_uncollectible':
            $invoice = $event->data->object;
        case 'invoice.paid':
            $invoice = $event->data->object;
        case 'invoice.payment_action_required':
            $invoice = $event->data->object;
        case 'invoice.payment_failed':
            $invoice = $event->data->object;
        case 'invoice.payment_succeeded':
            $invoice = $event->data->object;
            $abonnement = $abonnementRepository->findOneBy(['stripe_subscription_id' => $payloadDecoded->data->object->subscription, 'stripe_cus_id' => $payloadDecoded->data->object->customer]);

            if ($abonnement) {
                # code...
                //dd($abonnement->getFactures()->getValues());
                //Création de la facture potentielle relative à l'abonneement
                 $nouvelleFacturePotentielle = new Facture;
     
                 $statutFacture = $invoice->paid === "paid" ? true : false;
         
                 $nouvelleFacturePotentielle->setMontantHT($invoice->amount_paid/100);
                 $nouvelleFacturePotentielle->setDateEmissionFacture(new DateTime());
                 $nouvelleFacturePotentielle->setFactureAcquitee($statutFacture);
                 $nouvelleFacturePotentielle->setMontantTtcFacture($invoice->amount_paid/100);
                 $nouvelleFacturePotentielle->setAbonnement($abonnement);
                 $nouvelleFacturePotentielle->setPourcentageTva(20);
     
                 //Création du paiement relatif à l'abonnement (et donc à la facture)
                 $paiement = new Paiement();
                 $paiement->setMontantTtc($invoice->amount_paid/100);
                 $paiement->setPaid(true);
                 $paiement->setPaidAt(new DateTimeImmutable());
                 $paiement->setFacture($nouvelleFacturePotentielle);
     
                 $nouvelleFacturePotentielle->addPaiement($paiement);
     
                 $this->em->persist($nouvelleFacturePotentielle);
                 $this->em->persist($paiement);
     
                 $this->em->flush();
            }

           //dd($abonnement->getFactures()->getValues());

            return new Response('Nouvelle facture ajoutée');

        case 'invoice.sent':
            $invoice = $event->data->object;
        case 'invoice.upcoming':
            $invoice = $event->data->object;
        case 'invoice.updated':
            $invoice = $event->data->object;
        case 'invoice.voided':
            $invoice = $event->data->object;
        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
        
        $data =  ['invoice' => $invoice];
        $response = $this->json($data, 200, [], ['groups'=>'conversation:read']);

        return $response;
        /* return $this->render('web_hook/index.html.twig', [
            'controller_name' => 'WebHookController',
        ]); */
    }

    /**
     * @Route("/daily/payment/webhook", name="daily_payment_webhook")
     */
    public function dailyPaymentWebhook(Request $request){
        
        $payload = json_decode($request->getContent());
        
        \Stripe\Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');
        //$payload = @file_get_contents('php://input');
        $payload = $request->getContent();
        
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
            json_decode($payload, true)
        );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        $invoice = [];
        // Handle the event
        switch ($event->type) {
        case 'invoice.created':
            $invoice = $event->data->object;
        case 'invoice.deleted':
            $invoice = $event->data->object;
        case 'invoice.finalization_failed':
            $invoice = $event->data->object;
        case 'invoice.finalized':
            $invoice = $event->data->object;
        case 'invoice.marked_uncollectible':
            $invoice = $event->data->object;
        case 'invoice.paid':
            $invoice = $event->data->object;
        case 'invoice.payment_action_required':
            $invoice = $event->data->object;
        case 'invoice.payment_failed':
            $invoice = $event->data->object;
        case 'invoice.payment_succeeded':
            $invoice = $event->data->object;
            
            /*
            
            $nouvelleFacturePotentielle = new Facture;

            $statutFacture = $invoice->paid === "paid" ? true : false;
    
            $nouvelleFacturePotentielle->setMontantHT($invoice->amount_paid/100);
            $nouvelleFacturePotentielle->setDateEmissionFacture(new DateTime());
            $nouvelleFacturePotentielle->setFactureAcquitee($statutFacture);
            $nouvelleFacturePotentielle->setMontantTtcFacture($invoice->amount_paid/100);
            $nouvelleFacturePotentielle->setAbonnement($abonnement);
            $nouvelleFacturePotentielle->setPourcentageTva(20); 
            
            */
        case 'invoice.sent':
            $invoice = $event->data->object;
        case 'invoice.upcoming':
            $invoice = $event->data->object;
        case 'invoice.updated':
            $invoice = $event->data->object;
        case 'invoice.voided':
            $invoice = $event->data->object;
        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
        
        $data =  ['invoice' => $invoice];
        $response = $this->json($data, 200, [], ['groups'=>'conversation:read']);

        $stripeEvent = new StripeEvent();
            
        $statutFacture = $invoice->paid === "paid" ? true : false;
        $stripeEvent->setLabel('Event test');
        $stripeEvent->setPaid($statutFacture);
        $stripeEvent->setPaid($statutFacture);
        $stripeEvent->setSubscriptionId($invoice->subscription);
        $stripeEvent->setCustomerId($invoice->customer);
        $stripeEvent->setCreatedAt(new DateTimeImmutable()); //amount_due
        $stripeEvent->setMontantTTCFacture($invoice->amount_due/100); //amount_due

        $this->em->persist($stripeEvent);
        $this->em->flush();

        return $response;
        //return $this->render('web_hook/index.html.twig', [
        //    'controller_name' => 'WebHookController',
        //]);
    }
}

/*\Stripe\Stripe::setApiKey('sk_test_VePHdqKTYQjKNInc7u56JBrQ');
        //$payload = @file_get_contents('php://input');
        $payload = $request->getContent();
        
        $event = null;
        try {
            $event = \Stripe\Event::constructFrom(
            json_decode($payload, true)
        );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
        case 'invoice.created':
            $invoice = $event->data->object;
        case 'invoice.deleted':
            $invoice = $event->data->object;
        case 'invoice.finalization_failed':
            $invoice = $event->data->object;
        case 'invoice.finalized':
            $invoice = $event->data->object;
        case 'invoice.marked_uncollectible':
            $invoice = $event->data->object;
        case 'invoice.paid':
            $invoice = $event->data->object;
        case 'invoice.payment_action_required':
            $invoice = $event->data->object;
        case 'invoice.payment_failed':
            $invoice = $event->data->object;
        case 'invoice.payment_succeeded':
            $invoice = $event->data->object;
        case 'invoice.sent':
            $invoice = $event->data->object;
        case 'invoice.upcoming':
            $invoice = $event->data->object;
        case 'invoice.updated':
            $invoice = $event->data->object;
        case 'invoice.voided':
            $invoice = $event->data->object;
        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
        
        $data =  ['invoice' => $invoice];
        $response = $this->json($data, 200, [], ['groups'=>'conversation:read']);

        return $response;
        //return $this->render('web_hook/index.html.twig', [
        //    'controller_name' => 'WebHookController',
        //]); 
    }*/
