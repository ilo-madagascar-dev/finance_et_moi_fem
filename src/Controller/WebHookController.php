<?php

namespace App\Controller;

use DateTime;
use DateTimeImmutable;
use App\Entity\Facture;
use App\Entity\Paiement;
use App\Entity\StripeEvent;
use App\Repository\AbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

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
            
            //dd($abonnement);

            if ($abonnement) {

                //dd($abonnement);

                $date_difference = date_diff(new DateTime(), $abonnement->getFactures()->getValues()[0]->getDateEmissionFacture());
                
                if($date_difference->d < 1) {
                    return new Response("Facture venant d'être créée. ");
                }                     
                
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
                 
                 $abonnement->addFacture($nouvelleFacturePotentielle);

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

                 //dd($nouvelleFacturePotentielle);
                 return new Response('Nouvelle facture ajoutée');
            }
            
            return new Response("Pas d'abonnement relatif à cette facture !!!!");

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
    public function dailyPaymentWebhook(Request $request, AbonnementRepository $abonnementRepository){
        
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

        $statutFacture = $invoice->status === "paid" ? true : false;
        $stripeEvent->setLabel('Event test');
        $stripeEvent->setPaid($statutFacture);
        $stripeEvent->setSubscriptionId($invoice->subscription);
        $stripeEvent->setCustomerId($invoice->customer);
        $stripeEvent->setCreatedAt(new DateTimeImmutable()); //amount_due
        $stripeEvent->setMontantTTCFacture($invoice->amount_due/100); //amount_due
        
        $this->em->persist($stripeEvent);
        $this->em->flush();

        return $response;
    }

    /**
     * @Route("/account/billing/webhook", name="account_billing_web_hook", methods={"POST"})
     */
    public function accountBillingWebHook(Request $request, AbonnementRepository $abonnementRepository, MailerInterface $mailer): Response
    {
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET']);
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

            
            //Si un abonnement existe
            if ($abonnement) {
                
                $date_difference = date_diff(new DateTime(), $abonnement->getFactures()->getValues()[0]->getDateEmissionFacture());
                
                if($date_difference->d < 1) {
                    return new Response("Facture venant d'être créée. ");
                }        

                //Détermination du montant HT
                $montantHT = 59;
                
                /**
                 * STARTER_MENSUEL_PRICE_ID=price_1JhVMsDd9O5GRESHUkFY2u1b
                 * ESSENTIEL_MENSUEL_PRICE_ID=price_1JhVQ4Dd9O5GRESHdiPVy78a
                 * STARTER_ANNUEL_PRICE_ID=price_1JhVTyDd9O5GRESHV8J7fRE4
                 * ESSENTIEL_ANNUEL_PRICE_ID=price_1JhVVADd9O5GRESHmTYt6nzu
                 * SOUS_COMPTE_PRICE_ID=price_1JhVWhDd9O5GRESH5JElyqUy
                 */
                if ($abonnement->getTypeAbonnement()->getPriceID() == $_ENV['STARTER_MENSUEL_PRICE_ID']) {
                    $montantHT = 59;
                }elseif ($abonnement->getTypeAbonnement()->getPriceID() == $_ENV['ESSENTIEL_MENSUEL_PRICE_ID']) {
                    $montantHT = 89;
                }elseif ($abonnement->getTypeAbonnement()->getPriceID() == $_ENV['STARTER_ANNUEL_PRICE_ID']) {
                    $montantHT = 590;
                }elseif ($abonnement->getTypeAbonnement()->getPriceID() == $_ENV['ESSENTIEL_ANNUEL_PRICE']) {
                    $montantHT = 890;
                }elseif ($abonnement->getTypeAbonnement()->getPriceID() == $_ENV['SOUS_COMPTE_PRICE_ID']) {
                    $montantHT = 49;
                }

                //Création de la facture relative à l'abonnement
                 $nouvelleFacturePotentielle = new Facture;
                 //dd($invoice->paid);
                 $statutFacture = $invoice->paid;
         
                 $nouvelleFacturePotentielle->setMontantHT($montantHT);
                 $nouvelleFacturePotentielle->setDateEmissionFacture(new DateTime());
                 $nouvelleFacturePotentielle->setFactureAcquitee($statutFacture);
                 $nouvelleFacturePotentielle->setMontantTtcFacture($invoice->amount_paid/100);
                 $nouvelleFacturePotentielle->setAbonnement($abonnement);
                 $nouvelleFacturePotentielle->setPourcentageTva(20);
                 
                 $abonnement->addFacture($nouvelleFacturePotentielle);
                
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

                 //dd($nouvelleFacturePotentielle);
                 //http_response_code(200);
                 

                /* define('DOMPDF_UNICODE_ENABLED', true);

                $imagePath =  $_SERVER["DOCUMENT_ROOT"].'/images/icon/favicon.png';

                // Configure Dompdf according to your needs
                $pdfOptions = new Options();
                $pdfOptions->set('defaultFont', 'Arial');
                $pdfOptions->set('isRemoteEnabled', true);
                
                // Instantiate Dompdf with our options
                $dompdf = new Dompdf($pdfOptions);

                // Retrieve the HTML generated in our twig file
                $html = $this->renderView('billing/billing_prototype_1.html.twig', [
                    'title' => "Facture financer et moi ... ",
                    'client' => $abonnement->getClient(),
                    'facture' => $nouvelleFacturePotentielle,
                    'imagePath' => $imagePath
                ]);
                
                // Load HTML to Dompdf
                $dompdf->loadHtml($html);
                
                // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
                $dompdf->setPaper('A4', 'portrait');

                // Render the HTML as PDF
                $dompdf->render();

                // Store PDF Binary Data
                $output = $dompdf->output();
                
                $name = md5(uniqid());

                // In this case, we want to write the file in the public directory
                $publicDirectory = $_SERVER['DOCUMENT_ROOT'] . '/my_pdfs';

                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/my_pdfs')) {
                    mkdir($_SERVER['DOCUMENT_ROOT'] . '/my_pdfs', 0777, true);
                }

                // e.g /var/www/project/public/mypdf.pdf
                $pdfFilepath =  $publicDirectory . "/" .$name . ".pdf";
                
                // Write file to the desired path
                file_put_contents($pdfFilepath, $output);
                
                $mail = (new TemplatedEmail())
                ->from(new Address('admin@femcreditconso.fr', 'Financer et moi'))
                ->to($abonnement->getClient()->getUser()->getUsername())
                ->cc('contact@financeetmoi.fr')
                ->subject("Facture d'abonnement Financer Et Moi")
                ->htmlTemplate('billing/billingEmailTemplate.html.twig')
                // attach aa file stream
                ->attachFromPath( $pdfFilepath );
                
                $mailer->send($mail);*/

                $today = new DateTime;
                $factureReference = $abonnement->getTypeAbonnement()->getReference() . '-' . $abonnement->getClient()->getId() . '-' . $today->format('H-i-s');

                /**
                 * Paramètres supplémentaires.
                 */
                //$nouvelAbonnementPotentiel->setActif(true);
                
                $nouvelleFacturePotentielle->setReference($factureReference);
                
                $this->em->persist($nouvelleFacturePotentielle);
                $this->em->flush();

                 return new Response('Nouvelle facture ajoutée');
            }
            
            return new Response("Pas d'abonnement relatif à cette facture !!!!");

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
