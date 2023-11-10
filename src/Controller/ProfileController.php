<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Form\ProfileEditType;
use Doctrine\ORM\EntityManagerInterface;


class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'profile')]
    public function showProfile(PostRepository $postRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $posts = $postRepository->findBy(['user' => $user]);

        return $this->render('profile/profil.html.twig', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    #[Route('/profil/edit', name: 'profile_edit')]
public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    // Création du formulaire
    $form = $this->createForm(ProfileEditType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrement des modifications
        $entityManager->persist($user);
        $entityManager->flush();

        // Message flash de succès
        $this->addFlash('success', 'Votre profil a été mis à jour.');

        // Redirection vers la page du profil
        return $this->redirectToRoute('profile');
    }

    // Affichage du formulaire
    return $this->render('profile/edit.html.twig', [
        'form' => $form->createView(),
    ]);
  }
}
