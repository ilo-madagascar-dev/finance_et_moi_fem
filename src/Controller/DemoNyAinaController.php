<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemoNyAinaController extends AbstractController
{
    /**
     * @Route("/demo/ny/aina", name="demo_ny_aina")
     */
    public function pageListInfo(ClientRepository $clientrepository,UserRepository $userRepository): Response
    {
        if($this->getUser()){
            $connUser=$this->getUser()->getEmail();
            $conClient=$clientrepository->findOneBy(['email'=>$connUser]);
            $cle_groupe="1622543601638x611830994992322700";
            $role=$this->getUser()->getRoles()[0];
            
            return $this->render('admin\components\listeDesAgencessous.html.twig', [
                'controller_name' => 'DemoNyAinaController',
                'client'=>$conClient,
                'groupe'=>$cle_groupe,
                'role'=>$role
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
    
