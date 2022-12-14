<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['constraints' =>[new NotBlank(), new Length(max: 255)]])
            ->add('description', TextareaType::class, ['constraints' =>[new Length(max: 400)]])
            ->add('content', TextareaType::class, ['constraints' =>[new NotBlank(), new Length(max: 2000)]])
            ->add('post', SubmitType::class);
    }


}