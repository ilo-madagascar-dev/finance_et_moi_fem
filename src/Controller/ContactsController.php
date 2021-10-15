<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Form\ContactsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactsController extends AbstractController
{
    /**
     * @Route("/contacts", name="contacts")
     */
    public function index(Request $request, EntityManagerInterface $em ): Response
    {
        $contacts = new Contacts();

        $contacts_form = $this->createForm(ContactsType::class, $contacts);
        $contacts_form->handleRequest($request);
        
        if ($contacts_form->isSubmitted() && $contacts_form->isValid()) { 
            dd($request);
            $em->persist($contacts);
            $em->flush();

            //$this->addFlash('success', 'Votre email est envoyé avec succes');
        }

        return $this->render('contacts/index.html.twig', [
            'controller_name' => 'ContactsController',
            'contacts_form'   => $contacts_form->createView()
        ]);
    }
}
