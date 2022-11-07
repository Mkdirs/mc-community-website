<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['mapped' => false, 'constraints' => [new NotBlank()]])
            ->add('password', PasswordType::class, ['mapped' => false, 'constraints' => [new NotBlank()]])
            ->add('password2', PasswordType::class, ['mapped' => false, 'label' => 'Repeat password', 'constraints' => [new NotBlank()]])
            ->add('register', SubmitType::class, ['label' => 'Register !']);
    }





}
