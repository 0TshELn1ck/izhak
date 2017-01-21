<?php

namespace Izhak\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();

            if (!$user || null === $user->getId()) {
                $form->add('plainPassword', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'required' => true,
                        'invalid_message' => 'Паролі не співпадають',
                    ]
                );
            }
        });

        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'required' => false,
                    'invalid_message' => 'Паролі не співпадають',
                ]
            )->add('enabled', CheckboxType::class, [
                'required' => false
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Izhak\UserBundle\Entity\User',
        ));
    }

//TODO add custom validation group, because admin can create password length 1

    public function getName()
    {
        return 'user_bundle_user_type';
    }
}
