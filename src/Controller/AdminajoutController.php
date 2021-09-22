<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminajoutController extends AbstractController
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
     * @Route("/adminajout", name="adminajout")
     */
    public function index(Request $request): Response
    {
        $admin= new Admin();
        $form=$this->createForm(AdminType::class,$admin);
         $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $this->em->persist($admin);
            $this->em->flush();
        }

        return $this->render('adminajout/index.html.twig', [
            'controller_name' => 'AdminajoutController',
            'formAdmin' => $form->createView()
        ]);
    }
}
