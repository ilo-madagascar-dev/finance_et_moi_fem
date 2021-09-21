<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\SousCompte;
use App\Form\AdminSousComptePasswordModifType;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
* Require ROLE_ADMIN for *every* controller method in this class.
*
* @IsGranted("ROLE_CLIENT")
*/
class SousCompteController extends AbstractController
{
    
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
            'souscompte'=>$souscompte,
            'groupe'=>$cle_groupe,
            'userVd'=>$userVd,
            'form'=>$form->createView(),
            'role'=>$role
        ]);
        
    }

    /**
     * @Route("/sous-compte/modif/password/{id}", name="sous_compte_modif_password")
     */
    public function modifSousComptePassword(SousCompte $sousCompte, Request $request, SousCompteRepository $souscompterepository, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $userRelatedToSousCompte = $sousCompte->getClient()->getUser();
        $userRole = $this->getUser()->getRoles()[0];

        if ($userRelatedToSousCompte->getId() !== $this->getUser()->getId()) {
            $this->addFlash('danger', "Ce sous-compte n'est pas le vôtre.");
            return $this->redirectToRoute('dash');
        }

        $form = $this->createForm(AdminSousComptePasswordModifType::class, $sousCompte);
        $form->handleRequest($request);
        
        //$userRelatedToFiliale = $sousCompte->getUser();//Password encrypting
        if ($form->isSubmitted() && $form->isValid()) {
            $encryptedPassword = $passwordEncoder->encodePassword($userRelatedToSousCompte, $sousCompte->getPassword());
            
            $sousCompte->setPassword($encryptedPassword);
            $userRelatedToSousCompte->setPassword($encryptedPassword);

            $this->em->flush();

            $this->addFlash("success", "Le mot du passe du sous-compte a bien été modifié !!!!");
        }

        return $this->render('sous_compte/modif_password.html.twig',[
            'client' => $this->getUser()->getClient(),
            'form' => $form->createView(),
            'role'=> $userRole,
            'sousCompte' => $sousCompte
        ]);
    }
}
