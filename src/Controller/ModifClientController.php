<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Service\ApiService;
use App\Form\ClientModifType;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Form\ClientPasswordModifType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ModifClientController extends AbstractController
{
    /**
     * @Route("/agence/modif/{id}", name="modif_agence")
     */
    public function modifieAgence(Client $clients, Request $request, EntityManagerInterface $em, ApiService $apiService, UserRepository $userRepository, ClientRepository $clientRep)
    {   
        
        if (!$this->getUser()) {
            # code...
            //dd('vous devez connecté');
            return $this->redirectToRoute('app_login');
        }
        
        $abonnement = $this->getUser()->getClient()->getAbonnement()->getTypeAbonnement();
        //dd($this->getUser()->getClient()->getAbonnement());
        $present_mail = $clients->getEmail();

        $form = $this->createForm(ClientModifType::class, $clients);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            # code...
            $userRelatedToPotentialClient = $clients->getUser();
            //dd($userRelatedToPotentialClient);
            $userConnectedVd = $clients->getVd();
            $uniqid = $clients->getUniqid();

            if( $abonnement->getPriceID() === 'price_1JZs3OBW8SyIFHAgl3MjuPtc' || $abonnement->getPriceID() === 'price_1JZs71BW8SyIFHAgnS6niVw1' ){
                //dd("starter io");
                if( $clients->getEmail() !== $present_mail )
                {
                    $userExistence = $userRepository->findBy(['email' => $clients->getEmail()]);
                
                    if ($userExistence) {
    
                        //dd("loza !!!");
    
                        $this->addFlash('danger', 'Cet e-mail est déjà relié à un utilisateur');
    
                        return $this->redirectToRoute('modif_agence',['id' => $client->getId()]);
                    }
    
                }
                
                try{
                    $clientsInfosFromLenbox = $apiService->postLenbox($clients->getNomEntreprise(), $clients->getEmail(), $clients->getTelMobile(), $uniqid, true);
                }
                catch (Exception $e){
                    dd('Exception reçue : ', $e->getMessage() );
                }
    
                $userRelatedToPotentialClient->setEmail($clients->getEmail());
    
                $em->persist($clients);
                $em->flush();
            }
            elseif($abonnement->getPriceID() === 'price_1JZs5tBW8SyIFHAgHT2LqoM7' || $abonnement->getPriceID() === 'price_1JZs9wBW8SyIFHAgwZgSId5i')
            {
                $mimeTypeAllowed = ['application/pdf', 'image/jpeg', 'image/png'];
                if ($clients->getIdentityProofFile()) {

                    /**Si l'extension de la pièce-jointe du identityProofFile*/
                    if(!in_array($clients->getIdentityProofFile()->getMimeType(), $mimeTypeAllowed)){
                        
                        /** On réinitialise les champs pièces-jointes */
                        $clients->setIdentityProofFile(null);
                        $clients->setExtraitRCSFile(null);
                        $clients->setRibFile(null);

                        $this->addFlash('danger', "Seul les fichiers de type jpeg, png et pdf sont autorisés pour la pièce d'identité !!!!");
                        
                        return $this->redirectToRoute('modif_agence',['id' => $clients->getId()]);
                    }

                    $extension = explode('.', $clients->getIdentityProofFile()->getClientOriginalName())[1];
                    $filename = md5(uniqid()).'_'.md5(uniqid()).'_'.md5(uniqid()).'.'.$extension;
                    $clients->getIdentityProofFile()->move($_SERVER['DOCUMENT_ROOT'] .'/images/identityProof', $filename);
                    $clients->setIdentityProofFile(null);
                    $clients->setIdentityProof($filename);
                }

                if ($clients->getExtraitRCSFile()) {
                    
                    /**Si l'extension de la pièce-jointe du RCSFile n'est pas valide*/
                    if(!in_array($clients->getExtraitRCSFile()->getMimeType(), $mimeTypeAllowed)){
                        
                        /** On réinitialise les champs pièces-jointes */
                        $clients->setIdentityProofFile(null);
                        $clients->setExtraitRCSFile(null);
                        $clients->setRibFile(null);

                        $this->addFlash('danger', "Seul les fichiers de type jpeg, png et pdf sont autorisés pour l'extrait RCS !!!!");
                        
                        return $this->redirectToRoute('modif_agence',['id' => $clients->getId()]);
                    }
                    
                    $extension = explode('.', $clients->getExtraitRCSFile()->getClientOriginalName())[1];
                    $filename = md5(uniqid()).'_'.md5(uniqid()).'_'.md5(uniqid()).'.'.$extension;
                    $clients->getExtraitRCSFile()->move($_SERVER['DOCUMENT_ROOT'] .'/images/extrait_rcs', $filename);
                    $clients->setExtraitRCSFile(null);
                    $clients->setExtraitRCSname($filename);
                }

                if ($clients->getRibFile()) {

                    /**Si l'extension de la pièce-jointe du rib n'est pas valide*/
                    if(!in_array($clients->getRibFile()->getMimeType(), $mimeTypeAllowed)){
                        
                        /** On réinitialise les champs pièces-jointes */
                        $clients->setIdentityProofFile(null);
                        $clients->setExtraitRCSFile(null);
                        $clients->setRibFile(null);

                        $this->addFlash('danger', "Seul les fichiers de type jpeg, png et pdf sont autorisés pour la pièce-jointe du RIB !!!!");
                        
                        return $this->redirectToRoute('modif_agence',['id' => $clients->getId()]);
                    }
                    
                    $extension = explode('.', $clients->getRibFile()->getClientOriginalName())[1];
                    $filename = md5(uniqid()).'_'.md5(uniqid()).'_'.md5(uniqid()).'.'.$extension;
                    $clients->getRibFile()->move($_SERVER['DOCUMENT_ROOT'] .'/images/rib', $filename);
                    $clients->setRibFile(null);
                    $clients->setRib($filename);
                }

                if( $clients->getEmail() !== $present_mail )
                {
                        $userExistence = $userRepository->findBy(['email' => $clients->getEmail()]);
                    
                        if ($userExistence) {
        
                            $this->addFlash('danger', 'Cet e-mail est déjà relié à un utilisateur');
        
                            return $this->redirectToRoute('modif_agence',['id' => $clients->getId()]);
                        }
        
                }
                    
                try{
                    $clientsInfosFromLenbox = $apiService->postLenbox($clients->getNomEntreprise(), $clients->getEmail(), $clients->getTelMobile(), $uniqid, true);
                }
                catch (Exception $e){
                    dd('Exception reçue : ', $e->getMessage() );
                }
                    
        
                $userRelatedToPotentialClient->setEmail($clients->getEmail());
                
                $em->persist($clients);
                $em->flush();
                    
                return $this->redirectToRoute('modif_agence', ['id'=> $clients->getId()]);
            }

            return $this->redirectToRoute('modif_agence', ['id'=> $clients->getId()]);
        }
        //dd($abonnement);

        return $this->render('client/index.html.twig', [
            'client' => $this->getUser()->getClient(),
            'form' => $form->createView(),
            'role' => $this->getUser()->getRoles()[0],
            'type' => $abonnement,
        ]);
    }

    /**
     * @Route("/agence/modif/password/{id}", name="modif_password_agence")
     */
    public function modifPassword(Client $client, ClientRepository $clientrepos, Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, $id)
    {
        $relatedUser = $client->getUser();
        //dd();
        
        $userRole = $client->getUser()->getRoles()[0];
        
        if ($relatedUser->getClient()) {    
            if ($relatedUser->getClient()->getId() !== intval($id)) {
                $this->addFlash('danger', "Ce sous-compte n'est pas le vôtre.");
                return $this->redirectToRoute('dash');
            }
        }

        $form = $this->createForm(ClientPasswordModifType::class, $client);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $encryptedPassword = $passwordEncoder->encodePassword($relatedUser, $client->getPassword());
            
            $client->setPassword($encryptedPassword);
            $relatedUser->setPassword($encryptedPassword);

            //$em->persist($client)
            $em->flush();

            $this->addFlash("success", "Le mot du passe du compte a bien été modifié !!!!");
        }

        return $this->render('client/modifPassword.html.twig',[
            'client' => $this->getUser()->getClient(),
            'form' => $form->createView(),
            'role'=> $userRole,
            'sousCompte' => $client
        ]);
    }
}
