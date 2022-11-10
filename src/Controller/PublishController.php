<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostForm;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublishController extends AbstractController
{
    #[Route('/publish', name: 'publish')]
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
}
