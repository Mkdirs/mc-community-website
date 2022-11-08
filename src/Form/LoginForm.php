<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, ['mapped' => false, 'label' =>'username','constraints' => [new NotBlank()]])
            ->add('_password', PasswordType::class, ['mapped' => false, 'label' =>'Password', 'constraints' => [new NotBlank()]])
            ->add('submit', SubmitType::class, ['label' => 'Login !']);
    }





}
