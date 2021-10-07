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

class ClientType extends AbstractType
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
            ->add('identityProofFile', FileType::class, [
                'label' => 'Votre pièce d\'identité',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nom de votre entreprise',
                    'hidden' => 'hidden'
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
                    'Starter mensuel' => 'price_1JhYktDd9O5GRESHnFpw0RIY',
                    'Essentiel mensuel' => 'price_1JhYlvDd9O5GRESHGGpHRBtY',
                    'Starter annuel' => 'price_1JhYmrDd9O5GRESHSSYcJGNb',
                    'Essentiel annuel' => 'price_1JhYrKDd9O5GRESH9vmFlb33'
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
            ->add('extraitRCSFile', FileType::class, [
                'label' => 'Votre extrait RCS',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nom de votre entreprise',
                    'hidden' => 'hidden'
                )
            ])
            ->add('ribFile', FileType::class, [
                'label' => 'Pièce-jointe RIB',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'rib',
                    'hidden' => 'hidden'
                )
            ])
            ->add('legalStatusFile', FileType::class, [
                'label' => 'Pièce-jointe statut juridique',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Statut juridique',
                    'hidden' => 'hidden'
                )
            ])
            ->add('liasseFiscaleFile', FileType::class, [
                'label' => 'Pièce-jointe liasse fiscale',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Liasse fiscale',
                    'hidden' => 'hidden'
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
