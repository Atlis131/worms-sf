<?php

namespace App\Form;

use App\Entity\Weapon\Weapon;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeaponsType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array                $options
    ): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => '* Name',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mt-1 mb-2'
                ]
            ])
            ->add('baseVersion', EntityType::class, array(
                'label' => '* Base Version',
                'class' => Weapon::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('q')
                        ->andWhere('q.type = 0');
                },
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mt-1 mb-2'
                ]
            ))
            ->add('imageName', TextType::class, [
                'label' => 'Image name',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mt-1 mb-2'
                ]
            ])
            ->add('min', NumberType::class, [
                'label' => 'Minimum draw quantity',
                'required' => true,
                'html5' => true,
                'attr' => [
                    'class' => 'form-control mt-1 mb-2',
                    'min' => 1,
                    'max' => 10
                ]
            ])
            ->add('max', NumberType::class, [
                'label' => 'Maximum draw quantity',
                'required' => true,
                'html5' => true,
                'attr' => [
                    'class' => 'form-control mt-1 mb-2',
                    'min' => 1,
                    'max' => 10
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => '* Type',
                'required' => true,
                'choices' => [
                    'Regular' => Weapon::WEAPON_TYPE_REGULAR,
                    'Crafted' => Weapon::WEAPON_TYPE_CRAFTED
                ],
                'attr' => [
                    'class' => 'form-control mt-1 mb-2'
                ]
            ])
            ->add('isTool', ChoiceType::class, [
                'label' => '* Tool?',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mt-1 mb-2'
                ],
                'choices' => [
                    'No' => 0,
                    'Yes' => 1
                ],
            ])
            ->add('isOpenMapWeapon', ChoiceType::class, [
                'label' => '* Open map?',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mt-1 mb-2'
                ],
                'choices' => [
                    'No' => 0,
                    'Yes' => 1
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-primary pull-right mt-2'
                ]
            ]);
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void
    {
        $resolver->setDefaults([
            'data_class' => Weapon::class
        ]);
    }
}
