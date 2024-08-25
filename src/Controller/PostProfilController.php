<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostNewType;
use App\Service\SlugService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use IntlDateFormatter;

class PostProfilController extends AbstractController
{

    
    private $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    #[Route('/nouveau-post-profil', name: 'app_post_new_profil', methods: ['GET', 'POST'])]
    public function newProfilePost(Request $request, EntityManagerInterface $entityManager): Response
    {
        // $this->denyAccessUnlessGranted('POST_CREATE');
        $post = new Post();
        $post->setUser($this->getUser());
        $post->setDateCreation(new \DateTime()); 

        $form = $this->createForm(PostNewType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($post->getTitle(), Post::class);
            $post->setSlug($slug);

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Post ajouté.');

            return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post_profil/post_new_profil.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
   
}
