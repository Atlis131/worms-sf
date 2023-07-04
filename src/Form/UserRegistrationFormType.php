<?php

namespace App\Form;

use App\Form\Model\UserRegistrationFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('surname', TextType::class, [
                'label' => 'Surname',
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-Mail'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'constraints' => [
                    new Length(null, 8),
                    new Regex("/[a-z]/", 'Your password must contain at least one lowercase letter'),
                    new Regex("/[A-Z]/", 'Your password must contain at least one uppercase letter'),
                    new Regex("/\d/", 'Your password must contain at least one digit'),
                    new Regex("/[^a-zA-Z0-9]/", 'Your password must contain at least one special character'),
                    new Regex("/ /", 'Your password cannot contain spaces', null, false),
                    new NotCompromisedPassword()
                ],
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'placeholder' => 'Password',
                        'class' => 'form-control',
                        'style' => 'margin-bottom:12px'
                    ]
                ],
                'second_options' => [
                    'label' => 'Retype Password',
                    'attr' => [
                        'placeholder' => 'Retype Password',
                        'class' => 'form-control'
                    ]
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationFormModel::class,
        ]);
    }
}
