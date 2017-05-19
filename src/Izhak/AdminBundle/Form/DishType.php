<?php

namespace Izhak\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DishType extends AbstractType
{

    protected $categories = [
        'first_dish' => 'first_dish',
        'second_dish' => 'second_dish',
        'garnish' => 'garnish',
        'salad' => 'salad',
        'other' => 'other',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('category', ChoiceType::class, [
                'choices' => $this->categories,
                'placeholder' => 'Choose category',
                'empty_data' => false
            ])
            ->add('description', TextareaType::class)
            ->add('price', IntegerType::class, [
                'empty_data' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Izhak\AdminBundle\Entity\Dish']);
    }

    public function getName()
    {
        return 'admin_bundle_dish_type';
    }
}
