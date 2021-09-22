<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ChangePasswordFormType;

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
}
