<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{

    #[Route('/login', name: 'login')]
    public function test():Response{
        return $this->render('login.html.twig', ['title' => 'Login']);
    }
}