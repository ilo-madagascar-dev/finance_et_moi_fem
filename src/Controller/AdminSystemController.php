<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Facture;
use App\Entity\Paiement;
use App\Entity\Abonnement;
use App\Entity\SousCompte;
use Stripe\Checkout\Session;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\SousCompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AdminSystemController extends AbstractController
{
    /**
    * @var EntityManagerInterface $em
    */
    private $em;
	public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
    * @Route("/dashboard/Admin", name="dashboard-admin")
    */
    public function index(Request $request,UserRepository $userRepository): Response
    {
     if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $role=$this->getUser()->getRoles()[0];
            $conClient=$userRepository->findOneBy(['email'=>$connUser]);
            return $this->render('admin/components/admin-dashboard.html.twig', [
                'controller_name' => 'AdminDash',
                'admin'=>$conClient,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
    /**
    * @Route("/SuperAdmin/valid", name="Sup-admin_val")
    */
    public function valid(UserRepository $userRepository,ClientRepository $clientrepository): Response
    { 
     if($this->getUser()){
       $connUser=$this->getUser()->getEmail();
       $admincon=$userRepository->findOneBy(['email'=>$connUser]);
       $role=$admincon->getRoles()[0];
      if($role==='ROLE_ADMIN'){
            $client=$clientrepository->findAll();

            $urlidentityProof=$_ENV['DOWNLOAD_FILE'].'identityProof/';
            $urlrib=$_ENV['DOWNLOAD_FILE'].'rib/';
            $urlextrait_rcs=$_ENV['DOWNLOAD_FILE'].'extrait_rcs/';
            $urlliasseFiscal=$_ENV['DOWNLOAD_FILE'].'liasseFiscale/';
            $urllegalStatus=$_ENV['DOWNLOAD_FILE'].'legalStatus/';


            return $this->render('admin\components\listeDesAgencessous.html.twig', [
                'controller_name' => 'AdminSystemController',
                'clients'=>$client,
                'admin'=>$admincon,
                'urlIdentity'=>$urlidentityProof,
                'urlRib'=> $urlrib,
                'urlExtrait_rcs'=>$urlextrait_rcs,
                'urlLiasseFiscal'=>$urlliasseFiscal,
                'urlLegalStatus'=>$urllegalStatus,
                'role'=>$role,
            ]);
        }
        else {
                return $this->redirectToRoute('app_login');
            }
      }
       else {
            return $this->redirectToRoute('app_login');
        }
    }
    /**
    * @Route("/SuperAdmin/valid/{id}", name="Sup-admin_actif",methods={"POST|GET"})
    */
    public function actif(Request $request,UserRepository $userRepository,ClientRepository $clientrepository,$id): Response
    {
       $client=$clientrepository->find($id);
       $client->setActif(($client->getActif())?false:true);
       $this->em->persist($client);
       $this->em->flush();
       
       return new Response("true");
    }
    
}