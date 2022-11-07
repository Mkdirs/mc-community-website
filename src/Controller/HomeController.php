<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

class HomeController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    #[Route('/', name: 'home')]
    public function show(ManagerRegistry $doctrine) : Response{
        $posts = $doctrine->getRepository(Post::class)->findAll();
        return $this->render('home.html.twig', ['title' => 'Home', 'posts' => $posts]);
    }
}