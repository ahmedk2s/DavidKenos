<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use IntlDateFormatter;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $post = new Post();
        $post->setUser($this->getUser());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $categoryRepository->find(1);
            if ($category !== null) {
                $post->setCategory($category);
                $entityManager->persist($post);
                $entityManager->flush();

                return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error', 'La catégorie spécifiée n\'existe pas.');
            }
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }




    #[Route('/post-{id}', name: 'app_post_show', requirements: ['id' => '[a-zA-Z0-9\-_]+'], methods: ['GET'])]
    public function show(Post $post): Response
    {
        $dateCreation = $this->formatDate($post->getDateCreation());
        $dateEdition = $this->formatDate($post->getDateEdition());
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'dateCreation' => $dateCreation,
            'dateEdition' => $dateEdition,
        ]);
    }

    private function formatDate(\DateTimeInterface $date = null): string
    {
        if ($date === null) {
            return '';
        }

        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );

        return $formatter->format($date->getTimestamp());  
    }

    #[Route('/post-{id}/edit', name: 'app_post_edit', requirements: ['id' => '[a-zA-Z0-9\-_]+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setDateEdition(new \DateTime());  
            $entityManager->flush();  

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/post-{id}', name: 'app_post_delete', requirements: ['id' => '[a-zA-Z0-9\-_]+'], methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}

