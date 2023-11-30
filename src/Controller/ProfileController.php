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
use Symfony\Component\Filesystem\Filesystem;
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
    public function editProfile(Request $request, EntityManagerInterface $entityManager, string $slug): Response 
    {
        $loggedInUser = $this->getUser();
        if (!$loggedInUser) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $profileUser = $entityManager->getRepository(User::class)->findOneBy(['slug' => $slug]);
        if (!$profileUser) {
            throw $this->createNotFoundException('Profil non trouvé');
        }

        if ($loggedInUser->getId() !== $profileUser->getId()) {
    throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce profil.');
        }


        $form = $this->createForm(ProfileEditType::class, $profileUser);
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // /** @var UploadedFile $profilePictureFile */
        $profilePictureFile = $form->get('profilePictureFilename')->getData();

        if ($profilePictureFile) {
            $newFilename = uniqid().'.'.$profilePictureFile->guessExtension();

            try {
                $profilePictureFile->move(
                    $this->getParameter('products'), // Assurez-vous de configurer ce paramètre
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se passe mal lors du téléchargement du fichier
            }

            $user->setProfilePictureFilename($newFilename);
        }

         $coverPictureFile = $form->get('coverPictureFilename')->getData();

        if ($coverPictureFile) {
            $newFilename = uniqid().'.'.$coverPictureFile->guessExtension();

            try {
                $coverPictureFile->move(
                    $this->getParameter('products'), // Assurez-vous de configurer ce paramètre
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se passe mal lors du téléchargement du fichier
            }

            $user->setCoverPictureFilename($newFilename);
        }

            $slug = $this->slugService->createUniqueSlug($user->getFirstName() . ' ' . $user->getLastName(), User::class, $user->getId());
            $user->setSlug($slug);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Votre profil a été mis à jour.');
        return $this->redirectToRoute('profile');
    }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'profileUser' => $profileUser,
        ]);
    }
}