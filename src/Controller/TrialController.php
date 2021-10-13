<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Client;
use App\Entity\Facture;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use App\Repository\PaiementRepository;
use App\Repository\AbonnementRepository;
use Stripe\Stripe;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrialController extends AbstractController {
    /**
     * @Route("/client-trial/get", name="client_getter_trial")
     */
    public function clientGetter(PaiementRepository $paiementRepository, ClientRepository $clientRepository, UserRepository $userRepository, AbonnementRepository $abonnementRepository, FactureRepository $factureRepository):Response
    {
        $user = $userRepository->find(1);
        $usersAbonnement = $user->getClient()->getAbonnement();

        dd($usersAbonnement);
        
        //dd($user); */

        //$userClients = $this->getUser()->getClient()->getSouscomptes()->getValues();
        //dd($userClients);

        //$clients = $clientRepository->find(2);
        //dd($clients);
        
        //$abonnement = $abonnementRepository->findAll();
        //dd($abonnement);
        
        //$facture = $factureRepository->find(3);
        //dd($facture->getPaiements()->getValues());
        //dd($facture->getPaiements()->getKeys());
        /* $paiement = $paiementRepository->find(1);
        dd($paiement); */
        
        return new Response('Hello world');
    }

    /**
     * @Route("/image/getter", name="image_getter_hnts")
     */
    public function imageGetter(MailerInterface $mailer, ClientRepository $clientRepository, FactureRepository $factureRepository)
    {
        define('DOMPDF_UNICODE_ENABLED', true);
        
        $client = $clientRepository->find(20);
        $facture = $client->getAbonnement()->getFactures()[0];

        //dd($client, $facture);

        /* $client->setNom('Rafidinarivo');
        $client->setPrenom('Henintsoa');
        $client->setAddress('122 Rue of The Fisherman');
        

        $facture->setMontantHT(49);
        $facture->setMontantTtcFacture(58.8);
        $facture->setPourcentageTva(20); */

        $imagePath =  $_SERVER["DOCUMENT_ROOT"].'/images/icon/favicon.png';

        //dd($imagePath);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        
        //$pdfOptions->set('isRemoteEnabled', true);
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('billing/billing_prototype_1.html.twig', [
            'title' => "Facture financer et moi ... ",
            'client' => $client,
            'facture' => $facture,
            'imagePath' => $imagePath
        ]);
        //dd($imagePath);

        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        //$dompdf->set_option('isRemoteEnabled', TRUE);
        
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

        $mail = (new Email())
        ->from('fem.conso.credit@gmail.com')
        ->to('hentsraf@gmail.com')
        ->html(
            '
                <h2 style="text-align:center;">Votre facture abonnement FEM</h2>

                <p style="text-align:center;">Veuillez voir en pièce-jointe la facture relative à votre abonnement !!!!</p>
                    
            ')
        // attach a file stream
        ->attachFromPath( $pdfFilepath );

        $mailer->send($mail);
        
        return $this->render('billing/billing_prototype_1.html.twig', [
            'facture' => $facture,
            'client' => $client,
            'imagePath' => $imagePath
        ]);
    }

    /**
     * @Route("/email/rendering", name="email_rendering")
     */
    public function emailRendering(){
        return $this->render('billing/billingEmailTemplate.html.twig');
    }

    /**
     * @Route("/stripe/payment/trial", name="stripe_payment_trial")
     */
    public function livepayment()
    {
        Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');

        $priceId = 'price_1Jji0vDd9O5GRESHXgnEXe6K';

        $paymentSession = \Stripe\Checkout\Session::create([
            'success_url' => $this->generateUrl('live_payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('registration_payment_failed', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'line_items' => [[
                'price' => $priceId,
                // For metered billing, do not pass quantity
                'quantity' => 1,
            ]],
        ]);

        return $this->redirect($paymentSession->url, 303);
    }
    

    /**
     * @Route("/live/payment/success", name="live_payment_success")
     */
    public function livePaymentSuccess(){
        return $this->render('subscription/oneEuroSubscription.html.twig');
    }

    /**
     * @Route("/client/cancel/subscription/success/trial", name="client_cancel_trial")
     */
    public function clientCancelSubscriptionSuccessTrial(){
        return $this->render('subscription/client_cancel.html.twig');
    }
}