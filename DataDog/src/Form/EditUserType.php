<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',null,[
                'label' => 'Vardas'
            ])
            ->add('oldPassword', PasswordType::class, array(
                'mapped' => false,
                'label' => 'Senas slaptažodis'
            ))
            ->add('password', RepeatedType::class, array(
                'first_options'  => ['label' => 'Naujas slaptažodis'],
                'second_options' => ['label' => 'Pakartoti naują slaptažodį'],
                'type' => PasswordType::class,
                'invalid_message' => 'Nesutinka slaptažodžiai',
                'options' => array(
                    'attr' => array(
                        'class' => 'password-field'
                    )
                ),
                'required' => true,
                'mapped' => false
            ))
            ->add('email',EmailType::class,[
                'label' => 'Elektroninis paštas'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
