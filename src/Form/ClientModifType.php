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

class ClientModifType extends AbstractType
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
                    //'hidden' => 'hidden'
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
                    'Starter mensuel' => 'price_1JZs3OBW8SyIFHAgl3MjuPtc',
                    'Essentiel mensuel' => 'price_1JZs5tBW8SyIFHAgHT2LqoM7',
                    'Starter annuel' => 'price_1JZs71BW8SyIFHAgnS6niVw1',
                    'Essentiel annuel' => 'price_1JZs9wBW8SyIFHAgwZgSId5i'
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
                    //'hidden' => 'hidden'
                )
            ])
            ->add('ribFile', FileType::class, [
                'label' => 'Pièce-jointe RIB',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nom de votre entreprise',
                    //'hidden' => 'hidden'
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
