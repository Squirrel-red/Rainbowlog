<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Experience;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType as SymfonySearchType;

class ResearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('keyword', SymfonySearchType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Recherche...',
                ],
                'required' => false, 
            ])
            ->add('nearTown', SymfonySearchType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Near a town...',
                ],
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
    
        ]);
    }
}