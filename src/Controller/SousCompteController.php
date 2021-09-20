<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\SousCompte;
use App\Service\ApiService;
use App\Form\AdminSousCompteType;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\SousCompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SousCompteController extends AbstractController
{
    /**
     * @Route("sous/compte/modif/{id}", name="modif_sous_compte")
     */
    public function index(SousCompte $souscompte, Request $request, EntityManagerInterface $em, UserRepository $userRepository, SousCompteRepository $souscompterepository, ApiService $apiService): Response
    {
        $present_mail = $souscompte->getEmail();

        $connUser=$this->getUser()->getEmail();
            
        $userVd = $this->getUser()->getClient()->getVd();

        $role = $this->getUser()->getRoles()[0];
        
        $conClient=$souscompterepository->findOneBy(['email'=>$connUser]);

        $cle_groupe="1622543601638x611830994992322700";

        $form = $this->createForm(AdminSousCompteType::class, $souscompte);
        $form->handleRequest($request);
        //dd($souscompte->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            
            $userRelatedToPotentialClient = $souscompte->getUser();
            $userConnectedVd = $souscompte->getClient()->getVd();

            if( $souscompte->getEmail() !== $present_mail )
            {
                //dd("ts mtov");
                $userExistence = $userRepository->findBy(['email' => $souscompte->getEmail()]);
            
                if ($userExistence) {

                    $this->addFlash('danger', 'Cet e-mail est déjà relié à un utilisateur');

                    return $this->redirectToRoute('modif_sous_compte');
                }
            }
            
            //envoyer les modif a lenbox
            $clientsInfosFromLenbox = $apiService->postsousCompte($userConnectedVd, $souscompte->getEmail(), $souscompte->getTelMobile(), $souscompte->getNom(), $souscompte->getPrenom(), true);

            $userRelatedToPotentialClient->setEmail($souscompte->getEmail());

            $em->persist($souscompte);
            $em->flush();

        }
        
        return $this->render('sous_compte/index.html.twig', [
            'controller_name' => 'SousCompteController',
            'client'=>$this->getUser()->getClient(),
            'groupe'=>$cle_groupe,
            'userVd'=>$userVd,
            'form'=>$form->createView(),
            'role'=>$role
        ]); 
        
    }

    /***
     * 
     * 
     * 
     * 
       public function pageAjout(ClientRepository $clientrepository,UserRepository $userRepository, Request $request, SessionInterface $session): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            
            $userVd = $this->getUser()->getClient()->getVd();

            $role = $this->getUser()->getRoles()[0];

            $eventuallyNewSousCompte = new SousCompte;

            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1622543601638x611830994992322700";
            
            $form = $this->createForm(SousCompteType::class, $eventuallyNewSousCompte);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //dd($newClient);
                /**
                 * Récupération du vd de l'utilisateur connecté
                 *
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
    
                    return $this->redirectToRoute('registration');
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




     * 
     * public function sousCompteRegistrationPaymentSuccess(Request $request, SessionInterface $session, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, ApiService $apiService):Response
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
        //dd($potentialClient, $userConnectedVd);
        //Création d'un nouvel abonnement
        $nouvelAbonnementPotentiel = new Abonnement();
        
        $nouvelAbonnementPotentiel->setStripeSubscriptionId($stripe_session->subscription);
        $nouvelAbonnementPotentiel->setStripeCusId($stripe_session->customer);
        $nouvelAbonnementPotentiel->setMode($stripe_session->mode);
        $nouvelAbonnementPotentiel->setStatutPaiement($stripe_session->payment_status);
        $nouvelAbonnementPotentiel->setDateDebutAbonnement(new DateTime());
        $nouvelAbonnementPotentiel->setSousCompte($potentialClient);
        
        $session->set('abonnementPotentiel', $nouvelAbonnementPotentiel);
       
        //Création de l'entité user relatif au client
        $userRelatedToPotentialClient = new User;
        
        //Password encrypting
        $encryptedPassword = $passwordEncoder->encodePassword($userRelatedToPotentialClient, $potentialClient->getPassword());

        $userRelatedToPotentialClient->setEmail($potentialClient->getEmail());
        $userRelatedToPotentialClient->setPassword($encryptedPassword);
        $userRelatedToPotentialClient->setDateCreationUtilisateur(new DateTime());
        $userRelatedToPotentialClient->setActive(true);
        $userRelatedToPotentialClient->setRoles(["ROLE_SOUSCOMPTE"]);
        $clientsInfosFromLenbox = $apiService->postsousCompte($userConnectedVd, $potentialClient->getEmail(), $potentialClient->getTelMobile(), $potentialClient->getNom(), $potentialClient->getPrenom());
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

        return $this->render('sous-comptes/souscompte_success_payment.html.twig');
        }
     */
}
