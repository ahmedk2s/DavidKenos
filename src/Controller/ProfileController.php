<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Form\ProfileEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\SlugService;

class ProfileController extends AbstractController
{
    private $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }
    
    #[Route('/profil/{slug}', name: 'profile', defaults: ['slug' => null])]
public function showProfile(?string $slug, PostRepository $postRepository, EntityManagerInterface $entityManager): Response
{
    // Récupère l'utilisateur actuellement connecté
    $loggedInUser = $this->getUser();

    // Si aucun utilisateur n'est connecté, renvoie une erreur
    if (!$loggedInUser) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    // Si aucun slug n'est fourni dans l'URL, utilise le slug de l'utilisateur connecté
    if ($slug === null) {
        $slug = $loggedInUser->getSlug(); 
    }

    // Récupère l'utilisateur correspondant au slug fourni
    $user = $entityManager->getRepository(User::class)->findOneBy(['slug' => $slug]);

    // Si aucun utilisateur n'est trouvé pour le slug donné, renvoie une erreur
    if (!$user) {
        throw $this->createNotFoundException('Profil non trouvé');
    }

    $posts = $postRepository->findBy(['user' => $user]);

    return $this->render('profile/profil.html.twig', [
        'user' => $user,
        'posts' => $posts
    ]);
}

    #[Route('/modifier-{slug}', name: 'profile_edit')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager, string $slug): Response {
        $loggedInUser = $this->getUser();
        if (!$loggedInUser) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $profileUser = $entityManager->getRepository(User::class)->findOneBy(['slug' => $slug]);
        if (!$profileUser) {
            throw $this->createNotFoundException('Profil non trouvé');
        }

        // Vérifie si l'utilisateur connecté tente de modifier son propre profil
        if ($loggedInUser->getId() !== $profileUser->getId()) {
            // Vérifie si l'utilisateur connecté est un super administrateur
            if (in_array('ROLE_SUPER_ADMIN', $loggedInUser->getRoles())) {
            } else {
                throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce profil.');
            }
        }

        $form = $this->createForm(ProfileEditType::class, $profileUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour.');
            return $this->redirectToRoute('profile', ['slug' => $profileUser->getSlug()]);
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
