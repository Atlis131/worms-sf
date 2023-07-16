<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class UserChangePasswordType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array                $options
    ): void
    {
        if (!$options['resetPassword']) {
            $builder
                ->add('plainPassword', PasswordType::class, [
                    'required'    => false,
                    'constraints' => [
                        new UserPassword()
                    ],
                    'label'       => 'Current password',
                    'attr'        => [
                        'placeholder' => 'Current password',
                        'class'       => 'form-control mb-1'
                    ],
                ]);
        }
        $builder
            ->add('password', RepeatedType::class, [
                'type'           => PasswordType::class,
                'mapped'         => false,
                'constraints'    => [
                    new Length(null, 8),
                    new Regex("/[a-z]/", 'Your password must contain at least one lowercase letter'),
                    new Regex("/[A-Z]/", 'Your password must contain at least one uppercase letter'),
                    new Regex("/\d/", 'Your password must contain at least one digit'),
                    new Regex("/[^a-zA-Z0-9]/", 'Your password must contain at least one special character'),
                    new Regex("/ /", 'Your password cannot contain spaces', null, false),
                    new NotCompromisedPassword()
                ],
                'first_options'  => [
                    'label' => 'New password',
                    'attr'  => [
                        'placeholder' => 'New password',
                        'class'       => 'form-control mb-1'
                    ]
                ],
                'second_options' => [
                    'label' => 'Retype new password',
                    'attr'  => [
                        'placeholder' => 'Retype new password',
                        'class'       => 'form-control mb-2'
                    ]
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr'  => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void
    {
        $resolver->setDefaults([
            'data_class'    => User::class,
            'resetPassword' => false
        ]);
    }
}
