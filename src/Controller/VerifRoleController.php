<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VerifRoleController extends AbstractController
{
    /**
    * @Route("/Bienvenue", name="verif-role", methods="GET|POST")
    */
    public function controluser(Request $request,UserRepository $userepository): Response
    {
        $conuser=$this->getUser()->getUsername();
        $roleSouscompte='ROLE_SOUSCOMPTE';
        $roleclient='ROLE_CLIENT';
        $roleadmin='ROLE_ADMIN';
        $role=$userepository->findOneBy(['email'=>$conuser])->getRoles()[0];
        
        if($role===$roleSouscompte){
             return $this->redirectToRoute('dash');
        }else if($role===$roleclient){
             return $this->redirectToRoute('dash');
        }else if($role===$roleadmin){
             return $this->redirectToRoute('Sup-admin_val');  
        }else {
             return $this->redirectToRoute('registration');
        }
        
    }
    
}