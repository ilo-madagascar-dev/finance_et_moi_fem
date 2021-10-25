<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class oneEuroClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'e-mail',
                'attr' => array(
                    'placeholder' => 'Adresse email'
                )
            ])
            ->add('nom', TextType::class, [
                'label' => 'nom',
                'attr' => array(
                    'placeholder' => 'Nom'
                )
            ])
            ->add('prenom', TextType::class, [
                'label' => 'prenom',
                'attr' => array(
                    'placeholder' => 'Prénom'
                )
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password', 'attr' => array('placeholder' => 'Mot de passe')],
                'second_options' => ['label' => 'Repeat Password', 'attr' => array('placeholder' => 'Confirmez le mot de passe')],
            ])
            ->add('nomEntreprise', TextType::class, [
                'label' => 'nom de votre entreprise',
                'attr' => array(
                    'placeholder' => 'Nom de votre entreprise'
                )
            ])
            ->add('telMobile', TextType::class, [
                'label' => 'Téléphone mobile',
                'attr' => array(
                    'placeholder' => 'Téléphone mobile'
                )
            ])
            ->add('telFixe', TextType::class, [
                'label' => 'Téléphone fixe',
                'attr' => array(
                    'placeholder' => 'Téléphone fixe'
                )
            ])
            ->add('address', TextType::class, [
                'label' => 'adresse',
                'attr' => array(
                    'placeholder' => 'Adresse'
                )
            ])
            ->add('town', TextType::class, [
                'label' => 'Ville',
                'attr' => array(
                    'placeholder' => 'Ville'
                )
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'attr' => array(
                    'placeholder' => 'Code postal'
                )
            ])
            ->add('siren', TextType::class, [
                'label' => 'siren',
                'attr' => array(
                    'placeholder' => 'SIREN'
                )
            ])
            ->add('type_abonnement', ChoiceType::class, [
            'label' => 'Abonnement',
                'mapped' => false,
                'choices' => [
                    'Abonnement test' => $_ENV['ONE_EURO_PRICE_ID']
                ],
                'attr' => array(
                    'placeholder' => "Type d'abonnement"
                )
            ])
            ->add('statutEntreprise', ChoiceType::class, [
                'label' => 'Statut de l\'entreprise',
                    'choices' => [
                        'Entreprise individuelle' => 'Entreprise individuelle (EI)',
                        'Entreprise individuelle à responsabilité limitée' => 'Entreprise individuelle à responsabilité limitée (EIRL)',
                        'Entreprise unipersonnelle à responsabilité limitée' => 'Entreprise unipersonnelle à responsabilité limitée (EURL)',
                        'Société à responsabilité limitée' => 'Société à responsabilité limitée (SARL)',
                        'Société anonyme' => 'Société anonyme (SA)',
                        'Société par actions simplifiée unipersonnelle' => 'Société par actions simplifiée unipersonnelle (SASU)',
                        'Société par actions simplifiée' => 'Société par actions simplifiée (SAS)',
                        'Société en nom collectif' => 'Société en nom collectif (SNC)',
                        'Société en commandite simple' => 'Société en commandite simple (SCS)',
                        'Société en commandite par actions' => 'Société en commandite par actions (SCA)'
                    ],
                    'attr' => array(
                        'placeholder' => "Type d'abonnement"
                    )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
