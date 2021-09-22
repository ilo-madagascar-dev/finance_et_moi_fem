<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Admin;
use App\Form\AdminType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function index(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $admin= new Admin();
        $user = new User();
        $form=$this->createForm(AdminType::class,$admin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $encodepassword=$passwordEncoder->encodePassword($user,$admin->getPassword());
           $user->setEmail($admin->getEmail());
           $user->setRoles(['ROLE_ADMIN']);
           $user->setPassword($encodepassword);
           $user->setDateCreationUtilisateur(new DateTime());
           $user->setActive(true);
        $this->em->persist($user);
        $admin->setUser($user);
        $admin->setPassword($encodepassword);
        $this->em->persist($admin);
        $this->em->flush();

        return $this->redirectToRoute('Sup-admin',['id'=>$admin->getId()]);
        }

        return $this->render('adminajout/index.html.twig', [
            'controller_name' => 'AdminajoutController',
            'formAdmin' => $form->createView()
        ]);
    }
}
