<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Service\SlugService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/comment')]
class CommentController extends AbstractController
{
    #[Route('/index-des-commentaires', name: 'app_comment_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $postsWithComments = $postRepository->findPostsWithComments();
        
        return $this->render('comment/index.html.twig', [
            'posts' => $postsWithComments,
        ]);
    }

    #[Route('/nouveau-commentaire', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository, SlugService $slugService): Response
    {
        $comment = new Comment();
        $comment->setUser($this->getUser()); // Assurez-vous que cette méthode existe dans votre entité Comment
        $comment->setDateCreation(new \DateTime()); // Définir la date de création

        $posts = $postRepository->findAll();
        $form = $this->createForm(CommentType::class, $comment, [
            'posts' => $posts,
            'current_user' => $this->getUser(), // Passez l'utilisateur actuel
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugService->createUniqueSlug($comment->getText(), Comment::class);
            $comment->setSlug($slug); // Assurez-vous que cette méthode existe dans votre entité Comment

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès.');
            return $this->redirectToRoute('app_comment_index');
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier-{slug}', name: 'app_comment_edit', requirements: ['slug' => '[a-zA-Z0-9\-_]+'], methods: ['GET', 'POST'])]
public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager, SlugService $slugService): Response
{
    $currentPost = $comment->getPost();
    $currentUser = $comment->getUser(); // Récupérez l'utilisateur actuel du commentaire

    if (!$currentPost) {
        // Gérer l'erreur ou rediriger
        throw new \RuntimeException("Le commentaire n'a pas de post associé.");
    }

    $form = $this->createForm(CommentType::class, $comment, [
        'current_post' => $currentPost,
        'current_user' => $currentUser, // Passez l'utilisateur actuel au formulaire
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
            // Si le texte du commentaire a changé, mettez à jour le slug
            if ($comment->getText() !== $form->get('text')->getData()) {
                $slug = $slugService->createUniqueSlug($comment->getText(), Comment::class, $comment->getId());
                $comment->setSlug($slug);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Commentaire modifié avec succès.');
            return $this->redirectToRoute('app_comment_index');
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/supprimer-{id}', name: 'app_comment_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire supprimé avec succès.');
        }

        return $this->redirectToRoute('app_comment_index');
    }
}
