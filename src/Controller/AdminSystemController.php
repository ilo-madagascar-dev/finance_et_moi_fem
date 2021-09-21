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
    * @Route("/SuperAdmin", name="Sup-admin")
    */
    public function index(Request $request,UserRepository $userRepository): Response
    { 
       $nom["nom"] = "supeur Admin";
       $role[]="ROLE_SupAdmin";
        return $this->render('admin\components\listeDesAgencessous.html.twig', [
            'controller_name' => 'AdminSystemController',
            'client'=>$nom,
            'role'=>$role
        ]);
    }
    /**
    * @Route("/SuperAdmin/valid", name="Sup-admin_val")
    */
    public function valid(UserRepository $userRepository,ClientRepository $clientrepository): Response
    {
       $client=$clientrepository->findAll();
       $role[]="ROLE_SupAdmin";
        return $this->render('admin\components\validationsous.html.twig', [
            'controller_name' => 'AdminSystemController',
            'clients'=>$client,
            'role'=>$role,
        ]);
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
    //    $role[]="ROLE_SupAdmin";
    //     return $this->render('admin\components\validationsous.html.twig', [
    //         'controller_name' => 'AdminSystemController',
    //         'clients'=>$client,
    //         'role'=>$role,
    //          'result'=>$donnees
    //     ]);
    }
    
}