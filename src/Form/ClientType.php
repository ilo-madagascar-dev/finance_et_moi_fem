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
                    'Starter mensuel' => 'price_1JWc1BBW8SyIFHAgvuKoItbD',
                    'Essentiel mensuel' => 'price_1JT0YJBW8SyIFHAgmEuizs6Z',
                    'Starter annuel' => 'price_1JWc1oBW8SyIFHAgGnAmvtyw',
                    'Essentiel annuel' => 'price_1JWc3mBW8SyIFHAg2E4YGU4c',
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
