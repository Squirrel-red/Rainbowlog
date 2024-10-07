<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('dateMessage', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('seen')
            ->add('text')
            // ->add('sender', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            ->add('receiver', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'pseudo',
                // 'disabled' => true, // --> Pour blocker le contenu du champ
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
