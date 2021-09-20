<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\SousCompteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemoNyAinaController extends AbstractController
{
    /**
     * @Route("/demo/ny/aina", name="demo_ny_aina")
     */
    public function pageListAgence(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $listClient=$clientrepository->findAll();
          
            // $cle_groupe="1622543601638x611830994992322700";
            // $role=$this->getUser()->getRoles()[0];
            
            return $this->render('admin\components\listeDesAgencessous.html.twig', [
                'controller_name' => 'DemoNyAinaController',
                'clients'=>$listClient
                // 'groupe'=>$cle_groupe,
                // 'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    // /**
    //  * @Route("/demo/ny/aina/{id}", name="demo_aina")
    //  */
    // public function ListAgence(ClientRepository $clientrepository,SousCompteRepository $sousCompteRepository,UserRepository $userRepository,$id): Response
    // {
    //     if($this->getUser()){
    //         $connUser=$this->getUser()->getEmail();
    //         $Client=$clientrepository->find($id);
    //         // $mail=$Client->getEmail();
    //         // dd($mail);
    //         $listsousClient=$sousCompteRepository->findBy(['client'=>$Client]);
    //         dd($listsousClient);
          
    //         // $cle_groupe="1622543601638x611830994992322700";
    //         // $role=$this->getUser()->getRoles()[0];
            
    //         return $this->render('admin\components\listeDesAgencessous.html.twig', [
    //             'controller_name' => 'DemoNyAinaController',
    //             'clients'=>$listClient
    //             // 'groupe'=>$cle_groupe,
    //             // 'role'=>$role
    //         ]);
    //     } else {
    //         return $this->redirectToRoute('app_login');
    //     }
    // }

    /**
     * @Route("/demo/ny/valid", name="valid")
     */
    public function pageValidation(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1622543601638x611830994992322700";
            $role=$this->getUser()->getRoles()[0];
            
            return $this->render('admin\components\validationsous.html.twig', [
                'controller_name' => 'validationController',
                'client'=>$conClient,
                'groupe'=>$cle_groupe,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
    
