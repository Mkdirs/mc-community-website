<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    #[Route('/', name: 'home')]
    public function show(ManagerRegistry $doctrine, Request $req) : Response{
        //$name = $user->getUserIdentifier();
        //$passwd = $request->get('passwd');

        $user = $this->getUser();

        $name = $user == null ? '' : $user->getUserIdentifier();


        $posts = $doctrine->getRepository(Post::class)->findAll();
        return $this->render('home.html.twig', ['title' => 'Home', 'user' =>$name, 'posts' => $posts]);
    }

}