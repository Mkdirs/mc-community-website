<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    #[Route('/post/{id}', name: 'view_post')]
    public function view(PostRepository $repository, Request $request) : Response{

        $id = $request->get('id');
        $post = $repository->find($id);

        if($post == null)
            return new Response(status: 404);

        return $this->render('post.html.twig', ['title' => 'Post', 'post' => $post, 'user' => $this->getUser()]);
    }

}