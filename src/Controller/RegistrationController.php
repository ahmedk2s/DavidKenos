<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\SendMailService;
use App\Service\SlugService;
use App\Repository\UserRepository;
use App\Service\JWTService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private SlugService $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService; // Injection du SlugService
    }

    // #[Route('/register', name: 'app_register')]
    // public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->getUser()) {
    //         if ($this->isGranted('ROLE_ADMIN')) {
    //             return $this->redirectToRoute('app_admin');
    //         } elseif ($this->isGranted('ROLE_EMPLOYE')) {
    //             return $this->redirectToRoute('app_accueil');
    //         }
    //     }

    //     $user = new User();
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $slug = $this->slugService->createUniqueSlug($user->getFirstName() . ' ' . $user->getLastName(), User::class, $user->getId());
    //         $user->setSlug($slug);
    //         // encode the plain password
    //         $user->setRoles(['ROLE_SUPER_ADMIN']);

    //         $user->setPassword(
    //             $userPasswordHasher->hashPassword(
    //                 $user,
    //                 $form->get('plainPassword')->getData()
    //             )
    //         );

    //         $entityManager->persist($user);
    //         $entityManager->flush();
    //         // do anything else you need here, like send an email

    //         return $this->redirectToRoute('app_login');
    //     }

    //     return $this->render('registration/super_admin_register.html.twig', [
    //         'registrationForm' => $form->createView(),
    //     ]);
    // }

    #[Route('/register/admin', name: 'app_register_admin')]
    public function registerAdmin(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin');
            } elseif ($this->isGranted('ROLE_EMPLOYE')) {
                return $this->redirectToRoute('app_accueil');
            }
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($user->getFirstName() . ' ' . $user->getLastName(), User::class, $user->getId());
            $user->setSlug($slug);

            $user->setRoles(['ROLE_ADMIN']);

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );

            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('app_login');
        }

    return $this->render('registration/admin_register.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}

    #[Route('/register/employe', name: 'app_register_employe')]
    public function registerEmployee(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        SendMailService $mail,
        JWTService $jwt
    ): Response {

        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin');
            } elseif ($this->isGranted('ROLE_EMPLOYE')) {
                return $this->redirectToRoute('app_accueil');
            }
        }


        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->slugService->createUniqueSlug($user->getFirstName() . ' ' . $user->getLastName(), User::class, $user->getId());
            $user->setSlug($slug);
            $user->setRoles(['ROLE_EMPLOYE']);

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            $entityManager->persist($user);
            $entityManager->flush();


            $userId = $user->getId();


            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT'
            ];

            $payload = [
                'user_id' => $userId
            ];

            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));


            $mail->send(
                'no-reply@davisKenos.net',
                $user->getEmail(),
                'Activation de votre compte',
                'Activation',
                compact('user', 'token')
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/employe_register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UserRepository $usersRepository, EntityManagerInterface $entityManager): Response
    {

        $payload = $jwt->getPayload($token);


        $user = $usersRepository->find($payload['user_id']);


        if ($user && !$user->getIsVerified()) {
            $user->setIsVerified(true);
            $entityManager->flush($user);
            $this->addFlash('success', 'Utilisateur activé');
            return $this->redirectToRoute('profile');
        }

        // Si le token est invalide ou a expiré
        $this->addFlash('danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoiverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Cet utilisateur est déjà activé');
            return $this->redirectToRoute('profile');
        }


        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];


        $payload = [
            'user_id' => $user->getId()
        ];


        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // On envoie un mail
        $mail->send(
            'no-reply@monsite.net',
            $user->getEmail(),
            'Activation de votre compte',
            'activation',
            compact('user', 'token')
        );
        $this->addFlash('success', 'Email de vérification envoyé');
        return $this->redirectToRoute('profile');
    }
}
