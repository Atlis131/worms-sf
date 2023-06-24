<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function getName()
    {
        return 'user_form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr'     => [
                    'class' => 'form-control'
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr'     => [
                    'class' => 'form-control'
                ]
            ])
            ->add('surname', TextType::class, [
                'label' => 'Last name',
                'attr'     => [
                    'class' => 'form-control'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'choices' => [
                    'Administrator' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER'
                ],
                'expanded' => true,
                'multiple' => true,
            ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'Save',
            'attr' => [
                'class' => 'btn btn-primary'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\User'
        ]);
    }
}
