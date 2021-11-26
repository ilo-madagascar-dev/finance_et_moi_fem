<?php

namespace App\Controller;

use App\Entity\Abonnement;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Paiement;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use App\Repository\PaiementRepository;
use App\Repository\AbonnementRepository;
use App\Repository\TypeAbonnementRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;

class TrialController extends AbstractController {
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/client-trial/get", name="client_getter_trial")
     */
    public function clientGetter(PaiementRepository $paiementRepository, ClientRepository $clientRepository, UserRepository $userRepository, AbonnementRepository $abonnementRepository, FactureRepository $factureRepository):Response
    {
        $user = $userRepository->find(65);
        //$usersAbonnement = $user->getClient()->getAbonnement();
        $usersAbonnement = $user->getSousCompte()->getAbonnement();
        $usersFactures = $user->getSousCompte()->getAbonnement()->getFactures()->getValues();
        
        dd($usersFactures);
        $abonnement = $abonnementRepository->find(27);

        $client = $clientRepository->find(28);

        dd($client->getSousComptes()[0]->getUser()->getActive());
        
        //$abonnement = $abonnementRepository->findOneBy(['stripe_subscription_id' => "sub_1JkOjZDd9O5GRESHJmd2gvVw"]);
        //dd($abonnement);

        //dd($abonnement);

        //$date_difference = date_diff(new DateTime(), $abonnement->getFactures()->getValues()[2]->getDateEmissionFacture());


        //dd($date_difference);
        dd($abonnement->getFactures()->getValues());
        
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
        //Remplacer la clé api et le price_ID par la clé api live et le priceID live
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
     * @Route("/daily/payment/trial", name="daily_payment_trial")
     */
    public function dailyPaymentTrial(Request $request){
        Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');

        $priceId = 'price_1Jk7cxDd9O5GRESHtTiQbqeR';
        $success_url = $_ENV['DAILY_PAYMENT_REGISTRATION_SUCCESS_URL'];

        $paymentSession = \Stripe\Checkout\Session::create([
            'success_url' => $success_url,
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
     * @Route("/daily/payment/success", name="daily_payment_success")
     */
    public function dailyPaymentSuccess(Request $request, TypeAbonnementRepository $typeAbonnementRepository){
        $session_id = $request->get('session_id');
        $priceId = 'price_1JhVWhDd9O5GRESH5JElyqUy';

        $typeAbonnement = $typeAbonnementRepository->findOneBy(['price_ID' => $priceId]);

        Stripe::setApiKey($_ENV['STRIPE_SECRET']);

        $stripe_session = \Stripe\Checkout\Session::retrieve(
          $session_id,
          []
        );

        $nouvelAbonnementPotentiel = new Abonnement();
        $nouvelAbonnementPotentiel->setStripeSubscriptionId($stripe_session->subscription);
        $nouvelAbonnementPotentiel->setStripeCusId($stripe_session->customer);
        $nouvelAbonnementPotentiel->setMode($stripe_session->mode);
        $nouvelAbonnementPotentiel->setStatutPaiement($stripe_session->payment_status);
        $nouvelAbonnementPotentiel->setDateDebutAbonnement(new DateTime());
        //$nouvelAbonnementPotentiel->setClient($potentialClient);
        $nouvelAbonnementPotentiel->setTypeAbonnement($typeAbonnement);
        $nouvelAbonnementPotentiel->setActif(true);
        

        //Création de la facture potentielle relative à l'abonneement
        $nouvelleFacturePotentielle = new Facture;

        $statutFacture = $stripe_session->payment_status === "paid" ? true : false;
 
        $nouvelleFacturePotentielle->setMontantHT(1);
        $nouvelleFacturePotentielle->setDateEmissionFacture(new DateTime());
        $nouvelleFacturePotentielle->setFactureAcquitee($statutFacture);
        $nouvelleFacturePotentielle->setMontantTtcFacture($stripe_session->amount_total/100);
        $nouvelleFacturePotentielle->setAbonnement($nouvelAbonnementPotentiel);
        $nouvelleFacturePotentielle->setPourcentageTva(20);

        //Création du paiement relatif à l'abonnement (et donc à la facture)
        $paiement = new Paiement();
        $paiement->setMontantTtc($stripe_session->amount_total/100);
        $paiement->setPaid(true);
        $paiement->setPaidAt(new DateTimeImmutable());
        $paiement->setFacture($nouvelleFacturePotentielle);

        $nouvelleFacturePotentielle->addPaiement($paiement);

        $this->em->persist($nouvelleFacturePotentielle);
        $this->em->persist($nouvelAbonnementPotentiel);
        $this->em->persist($paiement);

        $this->em->flush();
        
        return $this->render('subscription/oneEuroSubscription.html.twig');
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

    /**
     * @Route("/fem-mailer/trial", name="fem_mailer_trial")
     */
    public function mailerTrial(MailerInterface $mailer)
    {
        $mail = (new TemplatedEmail())
        ->from(new Address('admin@femcreditconso.fr', 'Financer et moi'))
        ->to('henintsoa.rafidy@gmail.com')
        ->subject("Facture d'abonnement Financer Et Moi")
        ->html('<p>Lalala</p>');

        $mailer->send($mail);

        return new Response('Mail sent !!!!');
    }

    /**
     * @Route("/get/payment/method", name="get_payment_method")
     */
    public function getPaymentMethod()
    {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1'
          );
          
        $stripeData =  $stripe->paymentMethods->all([
            'customer' => 'cus_KORmSsUBgwDcOA',
            'type' => 'card',
          ]);

        return $this->json($stripeData);
    }

    /**
     * @Route("/update/payment/method", name="update_payment_method")
     */
    public function updatePaymentMethod()
    {
       $stripe = new \Stripe\StripeClient(
       'sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1'
       );
       $stripeData = $stripe->paymentMethods->update(
       'pm_1JjetFDd9O5GRESHknjF3nM5',
       ['card' => [
            //'number' => '4242424242424343',
            'exp_month' => 10,
            'exp_year' => 2022,
            'cvc' => '314',
        ]],
       );

       return $this->json($stripeData);
    }
}