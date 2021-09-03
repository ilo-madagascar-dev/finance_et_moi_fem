<?php

namespace App\Controller;

use App\Repository\ClientRepository;
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
        return $this->render('admin/admin.html.twig', [
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
}
