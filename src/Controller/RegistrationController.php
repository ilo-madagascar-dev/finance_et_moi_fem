<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Paiement;
use App\Entity\User;
use App\Form\ClientType;
use App\Repository\TypeAbonnementRepository;
use App\Repository\UserRepository;
use App\Service\ApiService;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Stripe\Stripe;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request, SessionInterface $session, UserRepository $userRepository, TypeAbonnementRepository $typeAbonnementRepository): Response
    {                
        $priceId = '';

        /**
         * Si le client arrive à partir de la page tarifs
         */
        if ($request->get('price_id')) {
            $priceId = $request->get('price_id');
            $session->set('price_id', $priceId);
        }
        
        $newClient = new Client();
        
        $form = $this->createForm(ClientType::class, $newClient);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * Récupération du tarif choisi par l'utilisateur
             */
            if ($request->request->get('client')['type_abonnement']) {
                $priceId = $request->request->get('client')['type_abonnement'];
                
                $session->set('price_id', $priceId);
            }

            /**
             * Vérification de l'existence du type d'abonnement dans la base
             */
            $typeAbonnement = $typeAbonnementRepository->findOneBy(['price_ID' => $priceId]);

            if (!$typeAbonnement) 
            {
                $this->addFlash('danger', "Ce type d'abonnement n'existe pas encore dans la base de données");

                return $this->redirectToRoute('registration');
            }
            
            //Les informations de la première étape seront enregistrées dans la premimère étape et seront flushées si l'utiliseur valide son abonnement et qu'il obtient un vd
            //Le User relatif à ce client ne sera créé que lorsque les deux dernières étapes (càd le paiement et la création d'un compte sur Lenbox seront validées) 
            $userExistence = $userRepository->findBy(['email' => $newClient->getEmail()]);
            
            if ($userExistence) {

                $this->addFlash('danger', 'Cet e-mail est déjà relié à un utilisateur');

                return $this->redirectToRoute('registration');
            }

            /**
             * Vérification de l'upload de pièces-jointes pour l'abonnement Essentiel
             */
            if($priceId == 'price_1JZs5tBW8SyIFHAgHT2LqoM7' || $priceId == 'price_1JZs9wBW8SyIFHAgwZgSId5i'){
                if(!$newClient->getIdentityProofFile()){
                    $this->addFlash('danger', "Vous devez uploader une copie de votre pièce d'identité pour l'abonnement Essentiel !!!!");
                    return $this->redirectToRoute('registration', ['price_id' => $priceId]);
                }

                if(!$newClient->getRibFile()){
                    $this->addFlash('danger', "Vous devez absolument rentrer votre RIB !!!!");
                    return $this->redirectToRoute('registration', ['price_id' => $priceId]);
                }

                if(!$newClient->getExtraitRCSFile()){
                    $this->addFlash('danger', "Vous devez absolument rentrer votre extrait RCS !!!!");
                    return $this->redirectToRoute('registration', ['price_id' => $priceId]);
                }

                if(!$newClient->getLegalStatusFile()){
                    $this->addFlash('danger', "Vous devez absolument uploader votre statut juridique !!!!");
                    return $this->redirectToRoute('registration', ['price_id' => $priceId]);
                }
            }
            
            
            /***************************************
             ****** GESTION DES PIECES JOINTES *****
             ******** ABONNEMENT ESSENTIEL ********
             **************************************/
            $mimeTypeAllowed = ['application/pdf', 'image/jpeg', 'image/png'];
            
            /* Envoi de la pièce jointe d'identité ? */
            if ($newClient->getIdentityProofFile()) {
                
                if(!in_array($newClient->getIdentityProofFile()->getMimeType(), $mimeTypeAllowed)){
                    $this->addFlash('danger', "Seul les fichiers de type jpeg, png et pdf sont autorisés pour la pièce d'identité !!!!");

                    return $this->redirectToRoute('registration', ['price_id' => $priceId]);
                }
            }

            /* Envoi de l'extrait CRSF ? */
            if ($newClient->getExtraitRCSFile()) {
                if(!in_array($newClient->getExtraitRCSFile()->getMimeType(), $mimeTypeAllowed)){
                    $this->addFlash('danger', "Seul les fichiers de type jpeg, png et pdf sont autorisés pour l'extrait RCS !!!!");
                    return $this->redirectToRoute('registration', ['price_id' => $priceId]);
                }
            }

            /* Pièce-jointe du rib ? */
            if ($newClient->getRibFile()) {
                if(!in_array($newClient->getRibFile()->getMimeType(), $mimeTypeAllowed)){
                    $this->addFlash('danger', "Seul les fichiers de type jpeg, png et pdf sont autorisés pour la pièce-jointe du RIB !!!!");
                    return $this->redirectToRoute('registration', ['price_id' => $priceId]);
                }
            }

            /* Statut juridique de la société ? */
            if ($newClient->getLegalStatusFile()) {
                if(!in_array($newClient->getLegalStatusFile()->getMimeType(), $mimeTypeAllowed)){
                    $this->addFlash('danger', "Seul les fichiers de type jpeg, png et pdf sont autorisés pour la pièce-jointe du statut juridique de la société !!!!");
                    return $this->redirectToRoute('registration', ['price_id' => $priceId]);
                }
            }

            /* Upload de la pièce d'identité */
            if ($newClient->getIdentityProofFile()) 
            {
                $extension = explode('.', $newClient->getIdentityProofFile()->getClientOriginalName())[1];
                $filename = md5(uniqid()).'_'.md5(uniqid()).'_'.md5(uniqid()).'.'.$extension;
                $newClient->getIdentityProofFile()->move($_SERVER['DOCUMENT_ROOT'] .'/images/identityProof', $filename);
                $newClient->setIdentityProofFile(null);
                $newClient->setIdentityProof($filename);
                
            }

            /* Upload de l'extrait CRSF */
            if ($newClient->getExtraitRCSFile()) 
            {    
                $extension = explode('.', $newClient->getExtraitRCSFile()->getClientOriginalName())[1];
                $filename = md5(uniqid()).'_'.md5(uniqid()).'_'.md5(uniqid()).'.'.$extension;
                $newClient->getExtraitRCSFile()->move($_SERVER['DOCUMENT_ROOT'] .'/images/extrait_rcs', $filename);
                $newClient->setExtraitRCSFile(null);
                $newClient->setExtraitRCSname($filename);
            }

            /* Upload de la pièce-jointe du rib */
            if ($newClient->getRibFile()) 
            {
                $extension = explode('.', $newClient->getRibFile()->getClientOriginalName())[1];
                $filename = md5(uniqid()).'_'.md5(uniqid()).'_'.md5(uniqid()).'.'.$extension;
                $newClient->getRibFile()->move($_SERVER['DOCUMENT_ROOT'] .'/images/rib', $filename);
                $newClient->setRibFile(null);
                $newClient->setRib($filename);
            }

            /* Upload du statut juridique de la société */
            if ($newClient->getLegalStatusFile()) 
            {
                $extension = explode('.', $newClient->getLegalStatusFile()->getClientOriginalName())[1];
                $filename = md5(uniqid()).'_'.md5(uniqid()).'_'.md5(uniqid()).'.'.$extension;
                $newClient->getLegalStatusFile()->move($_SERVER['DOCUMENT_ROOT'] .'/images/legalStatus', $filename);
                $newClient->setLegalStatusFile(null);
                $newClient->setLegalStatus($filename);
            }
            
            //Mise en place de l'utilisateur potentiel dans la session
            $session->set('possibleNewUser', $newClient);

            if ($session->get('possibleNewUser')) 
            {
                return $this->redirectToRoute('registration_second_step');
            } else {
                $this->addFlash('danger', 'Il y a eu un problème, veuillez ressoummettre le formulaire !!!');
            }

        }

        return $this->render('registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
            'form' => $form->createView(),
            'priceId' => $priceId
        ]);
    }

    /**
     * @Route("/registration/second-step", name="registration_second_step")
     */
    public function registrationSeconStep(SessionInterface $session)
    {
        /* 
        * Code permettant éventuellement de mieux sauvegarder le nouvel utilisateur probable dans la session
        $possibleNewClient = $session->get('possibleNewUser');
        $session->set('possibleNewUser', $possibleNewClient);
        */
        $fileUploadRappel = false;
        $price = 70.8;
        $montantHT = 59;

        if ($session->get('price_id')) {
            $priceId = $session->get('price_id');
         } else {
            $this->addFlash('danger', 'Aucun type d\'abonnement n\'a été choisi');
             return $this->redirectToRoute('registration');
         }
        
         //Afficher le message de rappel de la deuxième étape grâce à la variable $fileUploadRappel
        if ($priceId == 'price_1JZs5tBW8SyIFHAgHT2LqoM7' || $priceId == 'price_1JZs9wBW8SyIFHAgwZgSId5i') {
            $fileUploadRappel = true;
        }

         $priceArray = [
             'price_1JZs3OBW8SyIFHAgl3MjuPtc',
             'price_1JZs5tBW8SyIFHAgHT2LqoM7',
             'price_1JZs71BW8SyIFHAgnS6niVw1',
             'price_1JZs9wBW8SyIFHAgwZgSId5i'
         ];
 
         if (!in_array($priceId, $priceArray)) {
            $this->addFlash('danger', 'Le type d\'abonnement que vous avez choisi n\'existe pas');
            return $this->redirectToRoute('registration');
         }

         /**
          * Tests sur la valeur du price_id
          */

          $priceValues = [
            'starter_mensuel'=> 70.8,
            'essentiel_mensuel'=> 106.8,
            'starter_annuel'=> 708,
            'essentiel_annuel'=> 1068
        ];

        switch ($priceId) {
            case 'price_1JZs3OBW8SyIFHAgl3MjuPtc':
                $price = $priceValues['starter_mensuel'];
                $montantHT = 59;
                break;
            case 'price_1JZs5tBW8SyIFHAgHT2LqoM7':
                $price = $priceValues['essentiel_mensuel'];
                $montantHT = 89;
                break;
            case 'price_1JZs71BW8SyIFHAgnS6niVw1':
                $price = $priceValues['starter_annuel'];
                $montantHT = 590;
                break;
            case 'price_1JZs9wBW8SyIFHAgwZgSId5i':
                $price = $priceValues['essentiel_annuel'];
                $montantHT = 890;
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
        
        return $this->render('registration/secondStepRegistration_trial.html.twig', [
            'facture' => $facture,
            'rappel'=> $fileUploadRappel
        ]);
    }

    /**
     * @Route("/registration/payment", name="registration_payment") 
     */
    public function registrationPayment(SessionInterface $session):Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET']);
        $priceId = 'price_1JZs3OBW8SyIFHAgl3MjuPtc';

        if ($session->get('price_id')) {
           $priceId = $session->get('price_id');
        } else {
            $this->addFlash('danger', 'Aucun type d\'abonnement n\'a été choisi');
            return $this->redirectToRoute('registration');
        }

        $priceArray = [
            'price_1JZs3OBW8SyIFHAgl3MjuPtc',
            'price_1JZs5tBW8SyIFHAgHT2LqoM7',
            'price_1JZs71BW8SyIFHAgnS6niVw1',
            'price_1JZs9wBW8SyIFHAgwZgSId5i'
        ];

        if (!in_array($priceId, $priceArray)) 
        {
            $this->addFlash('danger', 'Le type d\'abonnement que vous avez choisi n\'existe pas');
            return $this->redirectToRoute('registration');
        }

        //Local success_url :
        //'success_url' => 'http://localhost:8000/registration/payment/success?session_id={CHECKOUT_SESSION_ID}',
        //Production success_url :
        //'success_url' => 'http://femcreditconso.fr/registration/payment/success?session_id={CHECKOUT_SESSION_ID}',
        $success_url = $_ENV['REGISTRATION_SUCCESS_URL'];

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
     * @Route("/registration/payment/success", name="registration_payment_success")
     */
    public function registrationPaymentSuccess(Request $request, SessionInterface $session, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, ApiService $apiService, TypeAbonnementRepository $typeAbonnementRepository, MailerInterface $mailer):Response
    {
        $session_id = $request->get('session_id');
        
        /**
         * Le priceId permettra de choisir l'abonnement équivalent dans la base de données
         */
        $priceId = 'price_1JZs3OBW8SyIFHAgl3MjuPtc';

        if ($session->get('price_id')) {
           $priceId = $session->get('price_id');
        } else {
            $this->addFlash('danger', "Aucun produit n'a été choisi");
            return $this->redirectToRoute('registration');
        }

        /**
         * Montant Hors-taxe relatif à l'abonnement
         */
        switch ($priceId) {
            case 'price_1JZs3OBW8SyIFHAgl3MjuPtc':
                $montantHT = 59;
                break;
            case 'price_1JZs5tBW8SyIFHAgHT2LqoM7':
                $montantHT = 89;
                break;
            case 'price_1JZs71BW8SyIFHAgnS6niVw1':
                $montantHT = 590;
                break;
            case 'price_1JZs9wBW8SyIFHAgwZgSId5i':
                $montantHT = 890;
                break;
        }

        $typeAbonnement = $typeAbonnementRepository->findOneBy(['price_ID' => $priceId]);

        if (!$typeAbonnement) 
        {
            $this->addFlash('danger', "Aucun type d'abonnement n'a été choisi (ou n'existe encore dans la base de données)");

            return $this->redirectToRoute('registration');
        }

        Stripe::setApiKey($_ENV['STRIPE_SECRET']);

        $stripe_session = \Stripe\Checkout\Session::retrieve(
          $session_id,
          []
        );

        if (!$stripe_session) {
            return $this->redirectToRoute('registration');
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
        $potentialClient->setUniqid($uniqId);
        $potentialClient->setVd($clientsVd);
        $potentialClient->setStripeToken(md5(uniqid()));
        $potentialClient->setPassword($encryptedPassword);
        $potentialClient->setUser($userRelatedToPotentialClient);
        $potentialClient->setAbonnement($nouvelAbonnementPotentiel);        
        $potentialClient->setAbonnement($nouvelAbonnementPotentiel);        
        
        if (!$potentialClient->getIdentityProof()) {
            $potentialClient->setUpdatedAt(new DateTime);
        }
        
        //$em->persist($potentialClient);
        //$em->flush();
    
        //Création de la facture potentielle relative à l'abonneement
        $nouvelleFacturePotentielle = new Facture;

        $statutFacture = $stripe_session->payment_status === "paid" ? true : false;
 
        $nouvelleFacturePotentielle->setMontantHT($montantHT);
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

        /**
         * Envoi du e-mail au client et à l'administrateur après la création du nouvel utilisateur
         */
        $email = (new TemplatedEmail())
            ->from(new Address('admin@femcreditconso.fr', 'Financer et moi'))
            ->to($userRelatedToPotentialClient->getEmail())
            ->cc('hentsraf@gmail.com')
            ->subject("Facture d'abonnement Financer Et Moi")
            ->htmlTemplate('billing/billing.html.twig')
            ->context([
                'facture' => $nouvelleFacturePotentielle,
                'client' => $potentialClient
            ])
        ;

        $mailer->send($email);

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
