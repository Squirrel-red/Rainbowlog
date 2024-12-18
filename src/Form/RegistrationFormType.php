<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        # on ajoute EmailType et la class
            ->add('email', EmailType::class)
            # on ajoute le champ "pseudo"
            ->add('pseudo', TextType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            # --> On remplace PasswordType par RepeatedType et on importe une class RepeatedType
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                # -->on remplace le code étant au-dessous par le code copié sur le site symfony.com pour repeated type symfony
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                // 'first_options'  => ['label' => 'Password'],
                // 'second_options' => ['label' => 'Repeat Password'],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 12,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new Regex([
                            'pattern' => '/[A-Z]/',
                            'message' => '1 uppercase leter',
                        ]),
                        new Regex([
                            'pattern' => '/[a-z]/',
                            'message' => '1 small letter',
                        ]),
                        new Regex([
                            'pattern' => '/\d/',
                            'message' => '1 digital',
                        ]),
                        new Regex([
                            'pattern' => '/\W/',
                            'message' => '1 special caracter ',
                        ]),
                        new PasswordStrength(),
                        new NotCompromisedPassword(),
                    ],
                    'label' => 'Password',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'The password fields must match.',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
