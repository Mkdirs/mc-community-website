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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{


    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $utils):Response{

        $lastUsername = $utils->getLastUsername() ?: '';
        $error = $utils->getLastAuthenticationError();

        //return new Response(content: join("\n", $response->headers->all()));

        return $this->render('login.html.twig', ['title' => 'Login', 'error' =>$error, 'lastUsername' =>$lastUsername]);
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

                    return $this->redirectToRoute('session', ['name' =>$username, 'password' => $hashedPasswd]);
                }else{
                    $form->addError(new FormError('User already exist !'));
                }
            }






        }
        return  $this->renderForm('register.html.twig', ['title' =>'Register', 'form' => $form]);
    }
}