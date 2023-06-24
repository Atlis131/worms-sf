<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeaponsType extends AbstractType
{

    public function getName()
    {
        return 'weapons_form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'    => 'Nazwa',
                'required' => true,
                'attr'     => [
                    'class' => 'form-control'
                ]
            ])
            ->add('imageName', TextType::class, [
                'label'    => 'Nazwa obrazka',
                'required' => true,
                'attr'     => [
                    'class' => 'form-control'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label'    => 'Typ',
                'required' => true,
                'choices'  => [
                    'Normalna'   => 0,
                    'Wytworzona' => 1
                ],
                'attr'     => [
                    'class' => 'form-control'
                ]
            ])
            ->add('isTool', ChoiceType::class, [
                'label'    => 'Narzędzie?',
                'required' => true,
                'attr'     => [
                    'class' => 'form-control'
                ],
                'choices'  => [
                    'Nie' => 0,
                    'Tak' => 1
                ],
            ])
            ->add('isOpenMapWeapon', ChoiceType::class, [
                'label'    => 'Na otwartą mape?',
                'required' => true,
                'attr'     => [
                    'class' => 'form-control'
                ],
                'choices'  => [
                    'Nie' => 0,
                    'Tak' => 1
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Zapisz',
                'attr'  => [
                    'class' => 'btn btn-primary pull-right mt-2'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Weapons'
        ]);
    }

}
