<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostForm;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
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

    #[Route('/publish', name: 'publish_post')]
    public function publish(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        if(! $user)
            return $this->redirectToRoute('login');

        $form = $this->createForm(PostForm::class, null);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $title = $form->get('title')->getData();
            $description = $form->get('description')->getData();
            $content = $form->get('content')->getData();

            $editor = $doctrine
                ->getRepository(User::class)
                ->findOneBy(['name' => $user->getUserIdentifier()]);

            $post = new Post();
            $post
                ->setTitle($title)
                ->setDescription($description)
                ->setContent($content)
                ->setEditor($editor);

            //La propriété 'editor' du post est modifié automatiquement par cette méthode
            $editor->addPost($post);


            //Sauvegarde des modifications
            $manager = $doctrine->getManager();
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('home');


        }

        return $this->renderForm('publish.html.twig', [
            'title' => 'Publish', 'user' => $user, 'form' =>$form
        ]);
    }

    #[Route('/post/{id}/edit', name: 'edit_post')]
    public function edit(Request $request, PostRepository $repository) : Response{
        $user = $this->getUser();
        if(! $user)
            return $this->redirectToRoute('login');

        $id = $request->get('id');
        $post = $repository->find($id);

        if($post == null)
            return new Response(status: 404);

        if($post->getEditor()->getName() != $user->getUserIdentifier())
            return new Response(status: 403);

        $form = $this->createForm(PostForm::class, $post);

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            $title = $form->get('title')->getData();
            $description = $form->get('description')->getData();
            $content = $form->get('content')->getData();

            $post->setTitle($title);
            $post->setDescription($description);
            $post->setContent($content);

            $repository->save($post, flush: true);

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('publish.html.twig', [
            'title' => 'Publish', 'user' => $user, 'form' =>$form
        ]);
    }

    #[Route('/post/{id}/delete', name: 'delete_post')]
    public function delete(Request $request, PostRepository $repository) : Response{
        $user = $this->getUser();
        if(! $user)
            return $this->redirectToRoute('login');

        $id = $request->get('id');
        $post = $repository->find($id);

        if($post == null)
            return new Response(status: 404);

        if($post->getEditor()->getName() != $user->getUserIdentifier())
            return new Response(status: 403);

        $repository->remove($post, flush: true);

        return $this->redirectToRoute('home');
    }

}