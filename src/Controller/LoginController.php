<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginForm;
use App\Form\RegisterForm;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{


    #[Route('/login', name: 'login')]
    public function login(Request $request, UserRepository $repository, UserPasswordHasherInterface $hasher):Response{

        $form = $this->createForm(LoginForm::class, null);


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $username = $form->get('username')->getData();
            $passwd = $form->get('password')->getData();



            $user = $repository
                ->findOneBy(['name' =>$username]);

            if($user != null && $hasher->isPasswordValid($user, $passwd)){
                return $this->redirectToRoute('home');
            }elseif ($user == null){
                $form->addError(new FormError('Unknown user !'));
            }else{
                $form->addError(new FormError('Wrong password !'));
            }


        }

        return $this->renderForm('login.html.twig', ['title' => 'Login', 'form' => $form]);
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher) : Response{
        $form = $this->createForm(RegisterForm::class, null);


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $username = $form->get('username')->getData();
            $passwd = $form->get('password')->getData();
            $passwd2 = $form->get('password2')->getData();

            if($passwd != $passwd2){
                $form->addError(new FormError('Passwords must be the same !'));
            }else{
                $entityManager = $doctrine->getManager();
                $user = $entityManager->getRepository(User::class)
                    ->findOneBy(['name' =>$username]);

                if($user == null){
                    $user = new User();
                    $hashedPasswd = $hasher->hashPassword($user, $passwd);
                    $user
                        ->setName($username)
                        ->setPassword($hashedPasswd)
                        ->setRegistrationDate(new \DateTime());

                    $entityManager->persist($user);
                    $entityManager->flush();
                    return $this->redirectToRoute('home');
                }else{
                    $form->addError(new FormError('User already exist !'));
                }
            }






        }
        return  $this->renderForm('register.html.twig', ['title' =>'Register', 'form' => $form]);
    }
}