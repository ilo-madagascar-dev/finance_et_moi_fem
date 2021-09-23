<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ChangePasswordFormType;
use App\Form\TypeAbonnementType;
use App\Entity\TypeAbonnement;

class ResetViewController extends AbstractController
{
    /**
     * @Route("/reset/view", name="reset_view")
     */
    public function index(): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class);
        // $form->handleRequest($request);
        return $this->render('reset_password/skeleton.html.twig', [
            'controller_name' => 'ResetViewController',
            'resetForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/success/pay", name="success_pay")
     */
    public function successPay(): Response
    {
        return $this->render('registration/successPayment.html.twig', [
            'controller_name' => 'successPayView',
        ]);
    }

    /**
     * @Route("/delete/form", name="delete_form")
     */
    public function deleteForm(): Response
    {
        $type_abonnement = array(
            "id" => "000fffeerer152",
            "type" => "unknown"
        );
        return $this->render('type_abonnement/_delete_form.html.twig', [
            'controller_name' => 'successPayView',
            "type_abonnement" => $type_abonnement,
        ]);
    }

    /**
     * @Route("/add/newa", name="newa")
     */
    public function addNewA(): Response
    {
        $typeAbonnement = new TypeAbonnement();
        $form = $this->createForm(TypeAbonnementType::class, $typeAbonnement);

        return $this->render('type_abonnement/new.html.twig', [
            'controller_name' => 'addA',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/newa", name="edita")
     */
    public function editA(): Response
    {
        $typeAbonnement = new TypeAbonnement();
        $form = $this->createForm(TypeAbonnementType::class, $typeAbonnement);

        $type_abonnement = array(
            "id" => "000fffeerer152",
            "type" => "unknown"
        );

        return $this->render('type_abonnement/edit.html.twig', [
            'controller_name' => 'editA',
            'form' => $form->createView(),
            "type_abonnement" => $type_abonnement,
        ]);
    }

    /**
     * @Route("/viewa", name="viewa")
     */
    public function viewTypes(): Response
    {
        $type_abonnement = array(
            "id" => "000fffeerer152",
            "type" => "unknown",
            "label" => "The label is a thing",
            "updatedAt" => date(22, 12),
            "createdAt" => date(12, 11)
        );
        return $this->render('type_abonnement/show.html.twig', [
            'controller_name' => 'viewTypes',
            "type_abonnement" => $type_abonnement,
        ]);
    }

    /**
     * @Route("/lista", name="lista")
     */
    public function listTypes(): Response
    {
        $type_abonnement = array(
            "id" => "000fffeerer152",
            "type" => "unknown",
            "label" => "The label is a thing",
            "updatedAt" => date(22, 12),
            "createdAt" => date(12, 11)
        );
        $type_abonnements= array(
            $type_abonnement,
            $type_abonnement,
            $type_abonnement,
            $type_abonnement,
            $type_abonnement
        );
        return $this->render('type_abonnement/index.html.twig', [
            'controller_name' => 'listTypes',
            "type_abonnements" => $type_abonnements,
        ]);
    }
}
