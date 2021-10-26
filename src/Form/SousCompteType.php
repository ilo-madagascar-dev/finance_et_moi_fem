<?php

namespace App\Form;

use App\Entity\SousCompte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class SousCompteType extends AbstractType
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
                'placeholder' => 'Nom du sous-compte'
            )])
            ->add('addresseEntreprise', TextType::class, ['attr' => array(
                'placeholder' => 'Adresse du sous-compte'
            )])
            ->add('codePostal', TextType::class, ['attr' => array(
                'placeholder' => 'Code postal'
            )])
            ->add('addresse', TextType::class, ['attr' => array(
                'placeholder' => 'Adresse'
            )])
            ->add('ville', TextType::class, ['attr' => array(
                'placeholder' => 'Ville'
            )])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => false, 'attr' => array('placeholder' => 'Mot de passe')],
                'second_options' => ['label' => false, 'attr' => array('placeholder' => 'Confirmez le mot de passe')],
                'attr' => ['placeholder' => 'Mot de passe']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SousCompte::class,
        ]);
    }
}
