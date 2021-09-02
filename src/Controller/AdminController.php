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
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'client'=>$conClient
        ]);
    }
}
