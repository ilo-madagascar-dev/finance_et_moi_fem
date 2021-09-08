<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/{id}", name="admin")
     */
    public function index(ClientRepository $clientrepository,$id): Response
    {
        $conClient=$clientrepository->find($id);
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'client'=>$conClient
        ]);
    }

    /**
     * @Route("/admin-h/{id}", name="admin-h")
     */
    public function indeheader(ClientRepository $clientrepository,$id): Response
    {
        $conClient=$clientrepository->find($id);
        return $this->render('admin/admin-header.html.twig', [
            'controller_name' => 'AdminHeaderController',
            'client'=>$conClient
        ]);
    }

    /**
     * @Route("/frame/{id}", name="frame")
     */
    public function frame(ClientRepository $clientrepository,$id): Response
    {
        $conClient=$clientrepository->find($id);
        return $this->render('admin/dashboard-frame.html.twig', [
            'controller_name' => 'FrameController',
            'client'=>$conClient
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
            $cle_groupe="1622543601638x611830994992322700";
            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminHeaderController',
                'client'=>$conClient,
                'groupe'=>$cle_groupe
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/dashboard", name="dash")
     */
    public function adminDash(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1622543601638x611830994992322700";
            return $this->render('admin/components/admin-dashboard.html.twig', [
                'controller_name' => 'AdminDash',
                'client'=>$conClient,
                'groupe'=>$cle_groupe
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/demande-financement", name="demfi")
     */
    public function demFi(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1622543601638x611830994992322700";
            return $this->render('admin/components/admin-demande-fin.html.twig', [
                'controller_name' => 'demFi',
                'client'=>$conClient,
                'groupe'=>$cle_groupe
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/suivi-dossier", name="suivi")
     */
    public function suiDoss(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1622543601638x611830994992322700";
            return $this->render('admin/components/admin-suivi-doss.html.twig', [
                'controller_name' => 'suiDoss',
                'client'=>$conClient,
                'groupe'=>$cle_groupe
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/payement-fractionne", name="payementF")
     */
    public function payFrac(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1622543601638x611830994992322700";
            return $this->render('admin/components/admin-payement-frac.html.twig', [
                'controller_name' => 'payFrac',
                'client'=>$conClient,
                'groupe'=>$cle_groupe
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/sajout", name="sajout")
     */
    public function pageAjout(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1622543601638x611830994992322700";
            return $this->render('admin/components/ajoutsous.html.twig', [
                'controller_name' => 'ajout',
                'client'=>$conClient,
                'groupe'=>$cle_groupe
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/saccueil", name="saccueil")
     */
    public function pageAccueil(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1622543601638x611830994992322700";
            return $this->render('admin/components/accueilsous.html.twig', [
                'controller_name' => 'Saccueil',
                'client'=>$conClient,
                'groupe'=>$cle_groupe
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/slistinfo", name="slistinfo")
     */
    public function pageListInfo(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1622543601638x611830994992322700";
            return $this->render('admin/components/listInfosous.html.twig', [
                'controller_name' => 'SlistInfo',
                'client'=>$conClient,
                'groupe'=>$cle_groupe
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
