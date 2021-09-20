<?php

namespace App\Form;

use App\Entity\SousCompte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class AdminSousCompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['attr' => array(
                'placeholder' => 'Nom'
            )])
            ->add('prenom', TextType::class, ['attr' => array(
                'placeholder' => 'Prénom'
            )])
            ->add('email', EmailType::class, ['attr' => array(
                'placeholder' => 'E-mail'
            )])
            ->add('telMobile', TextType::class, ['attr' => array(
                'placeholder' => 'Numéro mobile'
            )])
            ->add('nomEntreprise', TextType::class, ['attr' => array(
                'placeholder' => 'Nom de l\'entreprise'
            )])
            ->add('addresseEntreprise', TextType::class, ['attr' => array(
                'placeholder' => 'Addresse de l\entreprise'
            )])
            ->add('codePostal', TextType::class, ['attr' => array(
                'placeholder' => 'Code postal'
            )])
            ->add('addresse', TextType::class, ['attr' => array(
                'placeholder' => 'Addresse'
            )])
            ->add('ville', TextType::class, ['attr' => array(
                'placeholder' => 'Ville'
            )])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SousCompte::class,
        ]);
    }
}
