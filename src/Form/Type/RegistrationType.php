<?php

namespace Login\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('name');
        $builder->add('email');
        $builder->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options' => array('label' => 'Password'),
            'second_options' => array('label' => 'Confirm'),
        ));
    }
}
