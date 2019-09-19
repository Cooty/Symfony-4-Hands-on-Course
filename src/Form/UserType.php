<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('', ['data_class' => User::class]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class)
                ->add('email', EmailType::class)
                // RepeatedType - special Type for a from field you want to repeat
                // eg.: for confirmation
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Retype Password'],
                ])
                ->add('fullName', TextType::class)
                ->add('termsAgreed', CheckboxType::class, [
                    'mapped' => false, // this means that it's not present on the Entity, otherwise it would throw error because all the form fields have to match up with the entity class
                    // the same we added on the User entity with annotations
                    'constraints' => new IsTrue(),
                    'label' => 'I agree to the terms of service',
                ])
                ->add('Register', SubmitType::class);

    }
}