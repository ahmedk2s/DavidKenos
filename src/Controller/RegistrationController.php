<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use App\Form\RegistrationFormType;
use App\Service\SlugService;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
        MailerService $mailerService,
        TokenGeneratorInterface $tokenGeneratorInterface
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

            $tokenRegistration = $tokenGeneratorInterface->generateToken();

            $slug = $this->slugService->createUniqueSlug($user->getFirstName() . ' ' . $user->getLastName(), User::class, $user->getId());
            $user->setSlug($slug);
            $user->setRoles(['ROLE_EMPLOYE']);

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setTokenRegistration($tokenRegistration);

            $entityManager->persist($user);
            $entityManager->flush();

            $mailerService->send(
                $user->getEmail(),
                'Activation de votre compte',
                'Activation',
                [
                    'user' => $user,
                    'token' => $tokenRegistration,
                    'lifeTimeToken' => $user->getTokenRegistrationLifeTime()->format('d/m/y à H/hi')
                ]
            );

            $userId = $user->getId();

            $this->addFlash('success', 'Votre compte a bien été créé, veuillez vérifier vos emails pour l\'activer.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/employe_register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify{token}/{id<\d+>}', name: 'user_verify', methods: ['GET'])]
    public function verify (string $token, User $user, EntityManagerInterface $entityManager): Response {

        if($user->getTokenRegistration() !== $token) {
            throw new AccessDeniedHttpException();
        }
        if($user->getTokenRegistration() === null) {
            throw new AccessDeniedHttpException();
        }
        if(new DateTime('now') > $user->getTokenRegistrationLifeTime()) {
            throw new AccessDeniedHttpException();
        }

        $user->setIsVerified(true);
        $user->setTokenRegistration(null);
        $entityManager->flush();


        $this->addFlash('success', 'Votre compte a bien été activé, vous pouvez maintenant vous connecter.');

        return $this->redirectToRoute('app_login');
    }

}
