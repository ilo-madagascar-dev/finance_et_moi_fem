<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Stripe\Stripe;
use Dompdf\Options;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Facture;
use App\Entity\Paiement;
use App\Entity\Abonnement;
use App\Entity\SousCompte;
use App\Service\ApiService;
use App\Form\SousCompteType;
use Stripe\Checkout\Session;
use App\Repository\UserRepository;
use App\Repository\AdminRepository;
use App\Repository\ClientRepository;
use App\Repository\SousCompteRepository;
use App\Repository\TypeAbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class AdminController extends AbstractController
{
     /**
     * @Route("/admin/{id}", name="admin")
     */
    public function index(ClientRepository $clientrepository,$id): Response
    {
        $conClient=$clientrepository->find($id);
        $role=$this->getUser()->getRoles()[0];

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'client'=>$conClient,
            'role'=>$role
        ]);
    }

    /**
     * @Route("/admin-h/{id}", name="admin-h")
     */
    public function indeheader(ClientRepository $clientrepository,$id): Response
    {
        $conClient=$clientrepository->find($id);
        $role=$this->getUser()->getRoles()[0];
        return $this->render('admin/admin-header.html.twig', [
            'controller_name' => 'AdminHeaderController',
            'client'=>$conClient,
            'role'=>$role
        ]);
    }

    /**
     * @Route("/frame/{id}", name="frame")
     */
    public function frame(ClientRepository $clientrepository,$id): Response
    {
        $conClient=$clientrepository->find($id);
        $role=$this->getUser()->getRoles()[0];
        return $this->render('admin/dashboard-frame.html.twig', [
            'controller_name' => 'FrameController',
            'client'=>$conClient,
            'role'=>$role,
        ]);
    }

    /**
     * @Route("/client", name="client")
     */
    public function clientDash(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1627331075466x718359703300287400";
            $role=$this->getUser()->getRoles()[0];

            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminHeaderController',
                'client'=>$conClient,
                'groupe'=>$cle_groupe,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/dashboard", name="dash")
     */
    public function adminDash(ClientRepository $clientrepository, SousCompteRepository $sousCompteRepository, UserRepository $userRepository,AdminRepository $adminrepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $role=$this->getUser()->getRoles()[0];
            $vdScompte='';
            if($this->getUser()->getAdmin()){
                $conClient=$adminrepository->findOneBy(['email'=>$connUser]);
            }
            if ($this->getUser()->getClient()) {
                $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            }

            if ($this->getUser()->getSousCompte()) {
                $conClient=$sousCompteRepository->findOneBy(['email'=>$connUser]);
                $vdScompte=$conClient->getClient()->getVd();
            }
            return $this->render('admin/components/admin-dashboard.html.twig', [
                'controller_name' => 'AdminDash',
                'client'=>$conClient,
                'Scompte'=>$vdScompte,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

   /**
     * @Route("/demande-financement", name="demfi")
     */
    public function demFi(ClientRepository $clientrepository,UserRepository $userRepository,SousCompteRepository $sousCompteRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $role=$this->getUser()->getRoles()[0];
            $vdScompte='';
            if ($this->getUser()->getClient()) {
                $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
                 $url_demande_finance= $_ENV['IFRAME_DEMANDE_FINANCEMENT'];
                // dd($url_demande_finance);
            }

            if ($this->getUser()->getSousCompte()) {
                $conClient=$sousCompteRepository->findOneBy(['email'=>$connUser]);
                $vdScompte=$conClient->getClient()->getVd();
                $url_demande_finance= $_ENV['IFRAME_DEMANDE_FINANCEMENT'];
                //dd($url_demande_finance);
            }
            return $this->render('admin/components/admin-demande-fin.html.twig', [
                'controller_name' => 'demFi',
                'client'=>$conClient,
                'Scompte'=>$vdScompte,
                'demande_finance'=>$url_demande_finance,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/suivi-dossier", name="suivi")
     */
    public function suiDoss(ClientRepository $clientrepository,UserRepository $userRepository,SousCompteRepository $sousCompteRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $role=$this->getUser()->getRoles()[0];
            $vdScompte='';
            $cle_groupe=$_ENV['AUTHKEY'];
            if ($this->getUser()->getClient()) {
                $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
                 $iduniq=$conClient->getUniqid();
                 $url_suivi_dosier=$_ENV['IFRAME_SUIVI_DE_DOSSIER'].'idgroupe='.$cle_groupe.'&idagence='.$iduniq;
            
            }

            if ($this->getUser()->getSousCompte()) {
                $conClient=$sousCompteRepository->findOneBy(['email'=>$connUser]);
                $iduniq=$conClient->getClient()->getUniqid();
                $uid=$conClient->getUid();
                $url_suivi_dosier=$_ENV['IFRAME_SUIVI_DE_DOSSIER'].'idgroupe='.$cle_groupe.'&idagence='.$iduniq.'&idcompte='.$uid;
                
            }
           
            return $this->render('admin/components/admin-suivi-doss.html.twig', [
                'controller_name' => 'suiDoss',
                'client'=>$conClient,
                'Scompte'=>$vdScompte,
                'url_suivi_dosier'=>$url_suivi_dosier,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/payement-fractionne", name="payementF")
     */
    public function payFrac(ClientRepository $clientrepository,UserRepository $userRepository,SousCompteRepository $sousCompteRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $role=$this->getUser()->getRoles()[0];
            $vdScompte='';
            if ($this->getUser()->getClient()) {
                $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
                $actifpay=$conClient->getActif();
                $url_paiement_f=$_ENV['IFRAME_PAIEMENT_FRACTIONNE'];
            }

            if ($this->getUser()->getSousCompte()) {
                $conClient=$sousCompteRepository->findOneBy(['email'=>$connUser]);
                $vdScompte=$conClient->getClient()->getVd();
                $actifpay=$conClient->getClient()->getActif();
                $url_paiement_f=$_ENV['IFRAME_PAIEMENT_FRACTIONNE'];
            }

            return $this->render('admin/components/admin-payement-frac.html.twig', [
                'controller_name' => 'payFrac',
                'client'=>$conClient,
                'Scompte'=>$vdScompte,
                'url_paiement_f'=>$url_paiement_f,
                'role'=>$role,
                'agenceActif'=>$actifpay
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

     /**
     * @Route("/sous-comptes", name="saccueil")
     */
    public function pageAccueil(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1627331075466x718359703300287400";
            $role=$this->getUser()->getRoles()[0];

            return $this->render('admin/components/accueilsous.html.twig', [
                'controller_name' => 'Saccueil',
                'client'=>$conClient,
                'groupe'=>$cle_groupe,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/sous-compte-info", name="slistinfo")
     */
    public function pageListInfo(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1627331075466x718359703300287400";
            $role=$this->getUser()->getRoles()[0];
            
            return $this->render('admin/components/listInfosous.html.twig', [
                'controller_name' => 'SlistInfo',
                'client'=>$conClient,
                'groupe'=>$cle_groupe,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

     /**
     * @Route("/sous-comptes-liste", name="slist")
     */
    public function pageList(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);

            $userAccounts = $this->getUser()->getClient()->getSouscomptes()->getValues();

            //dd($usersAccounts);

            $cle_groupe="1627331075466x718359703300287400";
            $role = $this->getUser()->getRoles()[0];

            return $this->render('admin/components/listsous.html.twig', [
                'controller_name' => 'Slist',
                'client'=>$conClient,
                'groupe'=>$cle_groupe,
                'role'=>$role,
                'usersAccounts'=>$userAccounts
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/sous-compte-affiche/{id}", name="saffiche")
     */
    public function affiche(ClientRepository $clientrepository,UserRepository $userRepository,$id,SousCompteRepository $sousCompteRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $souCompte=$sousCompteRepository->findOneBy(['id'=>$id,'client'=>$conClient]);
            $cle_groupe="1627331075466x718359703300287400";
            $role = $this->getUser()->getRoles()[0];
            $vdScompte=$conClient->getVd();
            return $this->render('admin/components/monitor/dash-for-monitor.html.twig', [
                'controller_name' => 'Slist',
                'client'=>$conClient,
                'Soucompte'=>$souCompte,
                'Scompte'=>$vdScompte,
                'groupe'=>$cle_groupe,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/s-demande-financement/{id}", name="safficheDf")
     */
    public function afficheDf(ClientRepository $clientrepository,UserRepository $userRepository,$id,SousCompteRepository $sousCompteRepository): Response
    {
        if($this->getUser()){
            $vdScompte='';
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $souCompte=$sousCompteRepository->findOneBy(['id'=>$id,'client'=>$conClient]);
            $role = $this->getUser()->getRoles()[0];
            $vdScompte=$conClient->getVd();
            $url_demande_finance= $_ENV['IFRAME_DEMANDE_FINANCEMENT'];

            return $this->render('admin/components/monitor/monitor-demande-fin.html.twig', [
                'controller_name' => 'Slist',
                'client'=>$conClient,
                'Soucompte'=>$souCompte,
                'Scompte'=>$vdScompte,
                'url_demande_finance'=>$url_demande_finance,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
    
    /**
     * @Route("/s-payement-fractionne/{id}", name="saffichePf")
     */
    public function affichePf(ClientRepository $clientrepository,UserRepository $userRepository,$id,SousCompteRepository $sousCompteRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $souCompte=$sousCompteRepository->findOneBy(['id'=>$id,'client'=>$conClient]);
            $role = $this->getUser()->getRoles()[0];
            $vdScompte=$conClient->getVd();
            $actifpay=$conClient->getActif();
            $url_paiement_f=$_ENV['IFRAME_PAIEMENT_FRACTIONNE'];

            return $this->render('admin/components/monitor/monitor-payment-frac.html.twig', [
                'controller_name' => 'Slist',
                'client'=>$conClient,
                'Soucompte'=>$souCompte,
                'Scompte'=>$vdScompte,
                'role'=>$role,
                'url_paiement_f'=>$url_paiement_f,
                'agenceActif'=>$actifpay
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/s-suivi-dossier/{id}", name="safficheSd")
     */
    public function afficheSd(ClientRepository $clientrepository,UserRepository $userRepository,$id,SousCompteRepository $sousCompteRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $souCompte=$sousCompteRepository->findOneBy(['id'=>$id,'client'=>$conClient]);
            $cle_groupe=$_ENV['AUTHKEY'];
            $role = $this->getUser()->getRoles()[0];
            $iduniq=$conClient->getUniqid();
            $uid=$souCompte->getUid();
            $url_suivi_dosier=$_ENV['IFRAME_SUIVI_DE_DOSSIER'].'idgroupe='.$cle_groupe.'&idagence='.$iduniq.'&idcompte='.$uid;
            return $this->render('admin/components/monitor/monitor-suivi.html.twig', [
                'controller_name' => 'Slist',
                'client'=>$conClient,
                'Soucompte'=>$souCompte,
                'url_suivi_dosier'=>$url_suivi_dosier,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/ajouter-sous-compte", name="sajout")
     */
    public function pageAjout(ClientRepository $clientrepository,UserRepository $userRepository, Request $request, SessionInterface $session): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            
            $userVd = $this->getUser()->getClient()->getVd();

            $role = $this->getUser()->getRoles()[0];

            $eventuallyNewSousCompte = new SousCompte;

            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1627331075466x718359703300287400";
            
            $form = $this->createForm(SousCompteType::class, $eventuallyNewSousCompte);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //dd($newClient);
                /**
                 * Récupération du vd de l'utilisateur connecté
                 */
                if ($request->request->get('uservd')) {
                    $uservd = $request->request->get('uservd');
                    $session->set('userConnectedVd', $uservd);
                    $session->set('eventuallyNewSousCompte', $eventuallyNewSousCompte);
                }
                
                //Les informations de la première étape seront enregistrées dans la premimère étape et seront flushées si l'utiliseur valide son abonnement et qu'il obtient un vd
                //Le User relatif à ce client ne sera créé que lorsque les deux dernières étapes (càd le paiement et la création d'un compte sur Lenbox seront validées) 
                $userExistence = $userRepository->findBy(['email' => $eventuallyNewSousCompte->getEmail()]);
                
                if ($userExistence) {
    
                    $this->addFlash('danger', 'Cet e-mail est déjà relié à un utilisateur');
    
                    return $this->redirectToRoute('sajout');
                }
    
                $session->set('possibleNewSousCompte', $eventuallyNewSousCompte);
    
                if ($session->get('possibleNewSousCompte')) 
                {
                    return $this->redirectToRoute('sous-compte_ajout_second_step');
                } else {
                    $this->addFlash('danger', 'Il y a eu un problème, veuillez ressoummettre le formulaire !!!');
                }
    
            }

            return $this->render('admin/components/ajoutsous.html.twig', [
                'controller_name' => 'ajout',
                'client'=>$conClient,
                'groupe'=>$cle_groupe,
                'userVd'=>$userVd,
                'form'=>$form->createView(),
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    
    /**
     * @Route("/souscompte/ajout/second/step", name="sous-compte_ajout_second_step")
     */
    public function souscompteRegistrationSeconStep(SessionInterface $session)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if (!$session->get('userConnectedVd') || !$session->get('eventuallyNewSousCompte')) {
            return $this->redirectToRoute('sajout');
        }

        //Bil to show to the admin of the SousCompte
        $price = 58.80;
        $facture = new Facture;
        
        $facture->setMontantHT(49);
        $facture->setDateEmissionFacture(new DateTime());
        $facture->setMontantTtcFacture($price);
        $facture->setPourcentageTva(20);
        $facture->setFactureAcquitee(false);
        $session->set('facturePotentielle', $facture);

        return $this->render('sous-comptes/sous-compte-second-step-creation.html.twig', [
            'facture'=> $facture
        ]);
    }

    /**
     * @Route("/sous-compte/registration/payment", name="sous_compte_registration_payment") 
     */
    public function sousCompteRegistrationPayment(SessionInterface $session):Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET']);
        $priceId = $_ENV['SOUS_COMPTE_PRICE_ID'];

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $success_url = $_ENV['SOUS_COMPTE_REGISTRATION_SUCCESS_URL'];

        $paymentSession = \Stripe\Checkout\Session::create([
            'success_url' => $success_url,
            'cancel_url' => $this->generateUrl('sous-compte_registration_payment_failed', [], UrlGeneratorInterface::ABSOLUTE_URL),
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
     * @Route("/sous-compte/registration/payment/success", name="sous-compte_registration_payment_success")
     */
    public function sousCompteRegistrationPaymentSuccess(Request $request, SessionInterface $session, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, ApiService $apiService, MailerInterface $mailer, TypeAbonnementRepository $typeAbonnementRepository):Response
    {
        if (!$this->getUser()) {
            if (!$this->getUser()->getClient()) {
                return $this->redirectToRoute('app_login');
            }
        }

        if (!$session->get('userConnectedVd') || !$session->get('eventuallyNewSousCompte')) {
            return $this->redirectToRoute('sajout');
        }

        $session_id = $request->get('session_id');

        Stripe::setApiKey($_ENV['STRIPE_SECRET']);

        $stripe_session = \Stripe\Checkout\Session::retrieve(
          $session_id,
          []
        );

        $potentialClient = $session->get('eventuallyNewSousCompte');
        $userConnectedVd = $session->get('userConnectedVd');
        $priceId = $_ENV['SOUS_COMPTE_PRICE_ID'];
        $montantHT = 49;
        //dd($potentialClient, $userConnectedVd);
        
        //Création d'un nouvel abonnement 
        $nouvelAbonnementPotentiel = new Abonnement();
        $typeAbonnement = $typeAbonnementRepository->findOneBy(['price_ID' => $priceId]);

        if (!$typeAbonnement) 
        {
            $this->addFlash('danger', "Aucun type d'abonnement n'a été choisi (ou n'existe encore dans la base de données)");

            return $this->redirectToRoute('registration');
        }

        
        $nouvelAbonnementPotentiel->setStripeSubscriptionId($stripe_session->subscription);
        $nouvelAbonnementPotentiel->setStripeCusId($stripe_session->customer);
        $nouvelAbonnementPotentiel->setMode($stripe_session->mode);
        $nouvelAbonnementPotentiel->setStatutPaiement($stripe_session->payment_status);
        $nouvelAbonnementPotentiel->setDateDebutAbonnement(new DateTime());
        $nouvelAbonnementPotentiel->setSousCompte($potentialClient);
        $nouvelAbonnementPotentiel->setTypeAbonnement($typeAbonnement);
        //dd($nouvelAbonnementPotentiel);
        
        //$session->set('abonnementPotentiel', $nouvelAbonnementPotentiel);
       
        //Création de l'entité user relatif au client
        $userRelatedToPotentialClient = new User;
        
        //Password encrypting
        $encryptedPassword = $passwordEncoder->encodePassword($userRelatedToPotentialClient, $potentialClient->getPassword());

        $userRelatedToPotentialClient->setEmail($potentialClient->getEmail());
        $userRelatedToPotentialClient->setPassword($encryptedPassword);
        $userRelatedToPotentialClient->setDateCreationUtilisateur(new DateTime());
        $userRelatedToPotentialClient->setActive(true);
        $userRelatedToPotentialClient->setRoles(["ROLE_SOUSCOMPTE"]);

        /* Paramètres supplémentaires */
        $path = $_ENV['ENDPOINT_API_UID'];
        $authKey = $_ENV['AUTHKEY'];

        $clientsInfosFromLenbox = $apiService->postsousCompte($path, $authKey,$userConnectedVd, $potentialClient->getEmail(), $potentialClient->getTelMobile(), $potentialClient->getNom(), $potentialClient->getPrenom(), false);
        $sousCompteUid = $clientsInfosFromLenbox['response']['uid'];
        
        //$uniqId = md5(uniqid());

        //Données client à enregistrer
        $potentialClient->setUid($sousCompteUid);
        $potentialClient->setPassword($encryptedPassword);
        $potentialClient->setUser($userRelatedToPotentialClient);
        $potentialClient->setAbonnement($nouvelAbonnementPotentiel);        
        $potentialClient->setClient($this->getUser()->getClient());        
        $potentialClient->setCreateAt(new DateTimeImmutable());        

        $em->persist($potentialClient);
        $em->flush();
    
        //Création de la facture potentielle relative à l'abonneement
        $nouvelleFacturePotentielle = new Facture;

        $statutFacture = $stripe_session->payment_status === "paid" ? true : false;
 
        $nouvelleFacturePotentielle->setDateEmissionFacture(new DateTime());
        $nouvelleFacturePotentielle->setFactureAcquitee($statutFacture);
        $nouvelleFacturePotentielle->setMontantHT($montantHT);
        $nouvelleFacturePotentielle->setMontantTtcFacture($stripe_session->amount_total/100);
        $nouvelleFacturePotentielle->setAbonnement($nouvelAbonnementPotentiel);
        $nouvelleFacturePotentielle->setPourcentageTva(20);
 
        $session->set('facturePotentielle', $nouvelleFacturePotentielle);
        $facturePotentielle = $session->get('facturePotentielle');

        $em->persist($facturePotentielle);
        $em->flush();

        //Création du paiement relatif à l'abonnement (et donc à la facture)
        $paiement = new Paiement();
        $paiement->setMontantTtc($stripe_session->amount_total/100);
        $paiement->setPaid(true);
        $paiement->setPaidAt(new DateTimeImmutable());
        $paiement->setFacture($nouvelleFacturePotentielle);

        $nouvelleFacturePotentielle->addPaiement($paiement);

        $em->persist($paiement);
        $em->flush();

        //Envoi du mail au propriétaire du sous-compte
        define('DOMPDF_UNICODE_ENABLED', true);

        $imagePath =  $_SERVER["DOCUMENT_ROOT"].'/images/icon/favicon.png';

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('billing/billing_sous_compte_prototype_1.html.twig', [
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
        
        $mail = (new Email())
        ->from(new Address('admin@femcreditconso.fr', 'Financer et moi'))
        ->to($this->getUser()->getEmail())
        ->cc('contact@financeetmoi.fr')
        ->subject("Facture d'abonnement Financer Et Moi")
        ->html(
            '
                <h2 style="text-align:center;">Votre facture abonnement FEM</h2>

                <p style="text-align:center;">Veuillez voir en pièce-jointe la facture relative à l\' abonnement !!!!</p>
                    
            ')
        // attach a file stream
        ->attachFromPath( $pdfFilepath );

        $mailer->send($mail);
        
        $today = new DateTime;
        $factureReference = $typeAbonnement->getReference() . '-' . $potentialClient->getId() . '-' . $today->format('H-i-s');
        
        /**
         * Modification du paramètre actif de l'abonnement.
         */
        $nouvelAbonnementPotentiel->setActif(true);
        $nouvelleFacturePotentielle->setReference($factureReference);
        $em->flush();

        return $this->render('sous-comptes/souscompte_success_payment.html.twig');
    }

    /**
     * @Route("/sous-compte/registration/payment/failed", name="sous-compte_registration_payment_failed")
     */
    public function sousCompteRegistrationPaymentFailed():Response
    {
        //paymentFailure.html.twig
        return $this->render('registration/paymentFailure.html.twig');
    }
}
