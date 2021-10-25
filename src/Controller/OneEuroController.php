<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Stripe\Stripe;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\Client;
use DateTimeImmutable;
use App\Entity\Facture;
use App\Entity\Paiement;
use App\Form\ClientType;
use App\Entity\Abonnement;
use App\Form\oneEuroClientType;
use App\Service\ApiService;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeAbonnementRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/one/euro")
 */
class OneEuroController extends AbstractController
{
     /**
     * @Route("/registration", name="one_euro_registration")
     */
    public function index(Request $request, SessionInterface $session, UserRepository $userRepository, TypeAbonnementRepository $typeAbonnementRepository): Response
    {  
        if ($this->getUser()) {
            return $this->redirectToRoute('dash');
        }
        /**
         * Récupération des price_ID dans le fichier .env 
         * */
        $oneEuroPriceId = $_ENV['ONE_EURO_PRICE_ID'];
        
        /**
         * Initialisation d price_ID 
         * */
        $priceId = '';

        /**
         * Si le client arrive à partir de la page tarifs
         */
        if ($request->get('price_id')) {
            $priceId = $request->get('price_id');
            $session->set('price_id', $priceId);
        }
        //dd($priceId);
        
        $newClient = new Client();
        
        $form = $this->createForm(oneEuroClientType::class, $newClient);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * Récupération du tarif choisi par l'utilisateur
             */
            if ($request->request->get('one_euro_client')['type_abonnement']) {
                $priceId = $request->request->get('one_euro_client')['type_abonnement'];
                
                $session->set('price_id', $priceId);
            }


            //dd($priceId);

            /**
             * Vérification de l'existence du type d'abonnement dans la base
             */
            $typeAbonnement = $typeAbonnementRepository->findOneBy(['price_ID' => $priceId]);

            if (!$typeAbonnement) 
            {
                $this->addFlash('danger', "Ce type d'abonnement n'existe pas encore dans la base de données");

                return $this->redirectToRoute('one_euro_registration');
            }
            
            //Les informations de la première étape seront enregistrées dans la premimère étape et seront flushées si l'utiliseur valide son abonnement et qu'il obtient un vd
            //Le User relatif à ce client ne sera créé que lorsque les deux dernières étapes (càd le paiement et la création d'un compte sur Lenbox seront validées) 
            $userExistence = $userRepository->findBy(['email' => $newClient->getEmail()]);
            
            if ($userExistence) {

                $this->addFlash('danger', 'Cet e-mail est déjà relié à un utilisateur');

                return $this->redirectToRoute('one_euro_registration');
            }
           
            //Mise en place de l'utilisateur potentiel dans la session
            $session->set('possibleNewUser', $newClient);

            if ($session->get('possibleNewUser')) 
            {
                return $this->redirectToRoute('one_euro_registration_second_step');
            } else {
                $this->addFlash('danger', 'Il y a eu un problème, veuillez ressoummettre le formulaire !!!');
            }

        }

        return $this->render('registration/one_euro/index.html.twig', [
            'controller_name' => 'RegistrationController',
            'form' => $form->createView(),
            'priceId' => $priceId
        ]);
    }

    /**
     * @Route("/registration/second-step", name="one_euro_registration_second_step")
     */
    public function oneEuroRegistrationSeconStep(SessionInterface $session)
    {
        /* 
        * Code permettant éventuellement de mieux sauvegarder le nouvel utilisateur probable dans la session
        $possibleNewClient = $session->get('possibleNewUser');
        $session->set('possibleNewUser', $possibleNewClient);
        */
        $fileUploadRappel = false;
        $price = 1;
        $montantHT = 1;

        if ($session->get('price_id')) {
            $priceId = $session->get('price_id');
         } else {
            $this->addFlash('danger', 'Aucun type d\'abonnement n\'a été choisi');
             return $this->redirectToRoute('one_euro_registration');
         }

         $priceArray = [
             $_ENV['ONE_EURO_PRICE_ID']
         ];
 
         if (!in_array($priceId, $priceArray)) {
            $this->addFlash('danger', 'Le type d\'abonnement que vous avez choisi n\'existe pas');
            return $this->redirectToRoute('one_euro_registration');
         }

         /**
          * Tests sur la valeur du price_id
          */

          $priceValues = [
            'one_euro'=> 1
        ];

        switch ($priceId) {
            case $_ENV['ONE_EURO_PRICE_ID']:
                $price = $priceValues['one_euro'];
                $montantHT = 1;
                break;
        }

        //Billing to show to the view !!!!
        //$facture = $session->get('facturePotentielle');
        $facture = new Facture;

        $facture->setMontantHT($montantHT);
        $facture->setDateEmissionFacture(new DateTime());
        $facture->setMontantTtcFacture($price);
        $facture->setPourcentageTva(20);
        $facture->setFactureAcquitee(false);
        $session->set('facturePotentielle', $facture);
        
        return $this->render('registration/one_euro/secondStepRegistration_trial.html.twig', [
            'facture' => $facture,
            'rappel'=> $fileUploadRappel
        ]);
    }

    /**
     * @Route("/registration/payment", name="one_euro_registration_payment") 
     */
    public function registrationPayment(SessionInterface $session):Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET']);
        $priceId = $_ENV['ONE_EURO_PRICE_ID'];

        if ($session->get('price_id')) {
           $priceId = $session->get('price_id');
        } else {
            $this->addFlash('danger', 'Aucun type d\'abonnement n\'a été choisi');
            return $this->redirectToRoute('one_euro_registration');
        }
        
        $priceArray = [
            $_ENV['ONE_EURO_PRICE_ID']
        ];

        //dd($_ENV['ONE_EURO_PRICE_ID']);

        if (!in_array($priceId, $priceArray)) 
        {
            $this->addFlash('danger', 'Le type d\'abonnement que vous avez choisi n\'existe pas');
            return $this->redirectToRoute('one_euro_registration');
        }

        //Local success_url :
        //'success_url' => 'http://localhost:8000/registration/payment/success?session_id={CHECKOUT_SESSION_ID}',
        //Production success_url :
        //'success_url' => 'http://femcreditconso.fr/registration/payment/success?session_id={CHECKOUT_SESSION_ID}',
        $success_url = $_ENV['ONE_EURO_REGISTRATION_SUCCESS_URL'];

        $paymentSession = \Stripe\Checkout\Session::create([
            'success_url' => $success_url,
            'cancel_url' => $this->generateUrl('one_euro_registration_payment_failed', [], UrlGeneratorInterface::ABSOLUTE_URL),
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
     * @Route("/registration/payment/success", name="one_euro_registration_payment_success")
     */
    public function registrationPaymentSuccess(Request $request, SessionInterface $session, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, ApiService $apiService, TypeAbonnementRepository $typeAbonnementRepository, MailerInterface $mailer):Response
    {
        $session_id = $request->get('session_id');
        
        /**
         * Le priceId permettra de choisir l'abonnement équivalent dans la base de données
         */
        $priceId = $_ENV['STARTER_MENSUEL_PRICE_ID'];

        if ($session->get('price_id')) {
           $priceId = $session->get('price_id');
        } else {
            $this->addFlash('danger', "Aucun produit n'a été choisi");
            return $this->redirectToRoute('one_euro_registration');
        }

        /**
         * Montant Hors-taxe relatif à l'abonnement
         */
        switch ($priceId) {
            case $_ENV['STARTER_MENSUEL_PRICE_ID']:
                $montantHT = 59;
                break;
            case $_ENV['ESSENTIEL_MENSUEL_PRICE_ID']:
                $montantHT = 89;
                break;
            case $_ENV['STARTER_ANNUEL_PRICE_ID']:
                $montantHT = 590;
                break;
            case $_ENV['ESSENTIEL_ANNUEL_PRICE_ID']:
                $montantHT = 890;
                break;
        }

        $typeAbonnement = $typeAbonnementRepository->findOneBy(['price_ID' => $priceId]);

        if (!$typeAbonnement) 
        {
            $this->addFlash('danger', "Aucun type d'abonnement n'a été choisi (ou n'existe encore dans la base de données)");

            return $this->redirectToRoute('one_euro_registration');
        }

        Stripe::setApiKey($_ENV['STRIPE_SECRET']);

        $stripe_session = \Stripe\Checkout\Session::retrieve(
          $session_id,
          []
        );

        if (!$stripe_session) {
            return $this->redirectToRoute('one_euro_registration');
        }

        //dd($stripe_session);

        //Le client potentiel
        $potentialClient = $session->get('possibleNewUser');
       
        //Création d'un nouvel abonnement
        $nouvelAbonnementPotentiel = new Abonnement();
        
        $nouvelAbonnementPotentiel->setStripeSubscriptionId($stripe_session->subscription);
        $nouvelAbonnementPotentiel->setStripeCusId($stripe_session->customer);
        $nouvelAbonnementPotentiel->setMode($stripe_session->mode);
        $nouvelAbonnementPotentiel->setStatutPaiement($stripe_session->payment_status);
        $nouvelAbonnementPotentiel->setDateDebutAbonnement(new DateTime());
        $nouvelAbonnementPotentiel->setClient($potentialClient);
        $nouvelAbonnementPotentiel->setTypeAbonnement($typeAbonnement);
        
        //$session->set('abonnementPotentiel', $nouvelAbonnementPotentiel);
       
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

        $path = $_ENV['ENDPOINT_API_VD'];
        $authKey = $_ENV['AUTHKEY'];
        $clientsInfosFromLenbox = $apiService->postLenbox($path,$authKey,$potentialClient->getNomEntreprise(), $potentialClient->getEmail(), $potentialClient->getTelMobile(), $uniqId);
        $clientsVd = $clientsInfosFromLenbox['response']['vd'];
        
        //Données client à enregistrer
        $potentialClient->setUniqid($uniqId);
        $potentialClient->setVd($clientsVd);
        $potentialClient->setStripeToken(md5(uniqid()));
        $potentialClient->setPassword($encryptedPassword);
        $potentialClient->setUser($userRelatedToPotentialClient);
        $potentialClient->setAbonnement($nouvelAbonnementPotentiel);        
        $potentialClient->setActif(true);        
        
        if (!$potentialClient->getIdentityProof()) {
            $potentialClient->setUpdatedAt(new DateTime);
        }
        
        //$em->persist($potentialClient);
        //$em->flush();
    
        //Création de la facture potentielle relative à l'abonneement
        $nouvelleFacturePotentielle = new Facture;

        $statutFacture = $stripe_session->payment_status === "paid" ? true : false;
 
        $nouvelleFacturePotentielle->setMontantHT(1);
        $nouvelleFacturePotentielle->setDateEmissionFacture(new DateTime());
        $nouvelleFacturePotentielle->setFactureAcquitee($statutFacture);
        $nouvelleFacturePotentielle->setMontantTtcFacture($stripe_session->amount_total/100);
        $nouvelleFacturePotentielle->setAbonnement($nouvelAbonnementPotentiel);
        $nouvelleFacturePotentielle->setPourcentageTva(20);
 
        //$session->set('facturePotentielle', $nouvelleFacturePotentielle);
        //$facturePotentielle = $session->get('facturePotentielle');

        //$em->persist($facturePotentielle);
        //$em->flush();

        //Création du paiement relatif à l'abonnement (et donc à la facture)
        $paiement = new Paiement();
        $paiement->setMontantTtc($stripe_session->amount_total/100);
        $paiement->setPaid(true);
        $paiement->setPaidAt(new DateTimeImmutable());
        $paiement->setFacture($nouvelleFacturePotentielle);

        $nouvelleFacturePotentielle->addPaiement($paiement);

        $em->persist($nouvelleFacturePotentielle);
        $em->persist($potentialClient);
        $em->persist($paiement);

        $em->flush();

        define('DOMPDF_UNICODE_ENABLED', true);

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
            'client' => $potentialClient,
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
        ->to($userRelatedToPotentialClient->getEmail())
        ->cc('contact@financeetmoi.fr')
        ->subject("Facture d'abonnement Financer Et Moi")
        ->htmlTemplate('billing/billingEmailTemplate.html.twig')
        // attach aa file stream
        ->attachFromPath( $pdfFilepath );
        
        $mailer->send($mail);

        $today = new DateTime;
        $factureReference = $typeAbonnement->getReference() . '-' . $potentialClient->getId() . '-' . $today->format('H-i-s');

        /**
         * Paramètres supplémentaires.
         */
        $nouvelAbonnementPotentiel->setActif(true);
        
        $nouvelleFacturePotentielle->setReference($factureReference);
        
        $em->persist($nouvelleFacturePotentielle);
        $em->flush();

        return $this->render('registration/successPayment.html.twig');
    }

    /**
     * @Route("/registration/payment/failed", name="one_euro_registration_payment_failed")
     */
    public function registrationPaymentFailed():Response
    {
        //paymentFailure.html.twig
        return $this->render('registration/paymentFailure.html.twig');
    }
}
