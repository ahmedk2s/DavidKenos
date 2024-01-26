<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\SlugService;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use IntlDateFormatter;

#[Route('/admin/post')]
class PostController extends AbstractController
{

    private $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }
    
    #[Route('/index-des-posts', name: 'app_post_index', methods: ['GET'])]
public function index(PostRepository $postRepository): Response
{
    $user = $this->getUser();

    return $this->render('post/index.html.twig', [
        'posts' => $postRepository->findAll(),
        'user' => $user,
    ]);
}


   #[Route('/nouveau-post', name: 'app_post_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('POST_CREATE');
    $post = new Post();
    $post->setUser($this->getUser());
    $post->setDateCreation(new \DateTime()); 

    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $slug = $this->slugService->createUniqueSlug($post->getTitle(), Post::class);
        $post->setSlug($slug);

        $entityManager->persist($post);
        $entityManager->flush();

        $this->addFlash('success', 'Post ajouté.');

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('post/new.html.twig', [
        'post' => $post,
        'form' => $form->createView(),
    ]);
}



    // private function formatDate(\DateTimeInterface $date = null): string
    // {
    //     if ($date === null) {
    //         return '';
    //     }

    //     $formatter = new IntlDateFormatter(
    //         'fr_FR',
    //         IntlDateFormatter::LONG,
    //         IntlDateFormatter::NONE
    //     );

    //     return $formatter->format($date->getTimestamp());  
    // }

    #[Route('/modifier-{slug}', name: 'app_post_edit', requirements: ['slug' => '[a-zA-Z0-9\-_]+'], methods: ['GET', 'POST'])]
public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('POST_EDIT', $post);
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $slug = $this->slugService->createUniqueSlug($post->getTitle(), Post::class);
        $post->setSlug($slug);
        $post->setDateEdition(new \DateTime());

        $entityManager->flush();

        $this->addFlash('success', 'Post modifié.');

        return $this->redirectToRoute('app_post_index');
    }

    return $this->render('post/edit.html.twig', [
        'post' => $post,
        'form' => $form->createView(),
    ]);
}


    #[Route('/supprimer-{id}', name: 'app_post_delete', requirements: ['id' => '[a-zA-Z0-9\-_]+'], methods: ['POST'])]
public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('POST_DELETE', $post);
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Post supprimé.');

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}

