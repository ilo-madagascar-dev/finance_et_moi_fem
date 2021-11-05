<?php

namespace App\Form;

use App\Entity\SubscriptionSearch;
use App\Entity\TypeAbonnement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subscription', EntityType::class, [
                'class' => TypeAbonnement::class,
                'choice_label' => 'label',
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => "Type d'abonnement"
                ]
            ])
            ->add('town', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => "Ville"
                ]
            ])
            ->add('postalCode', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => "Code postal"
                ]
            ])
            ->add('dateDebutInterval', DateType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => "Début interval"
                ],
                'widget' => 'single_text',
            ])
            ->add('dateFinInterval', DateType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => "Fin interval"
                ],
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SubscriptionSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
