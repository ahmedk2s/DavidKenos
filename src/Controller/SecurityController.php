<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Check if the user is already authenticated, redirect to appropriate page based on roles
        if ($this->getUser()) {
            // Check roles and redirect accordingly
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin');
            } elseif ($this->isGranted('ROLE_EMPLOYE')) {
                return $this->redirectToRoute('app_accueil');
            }
        }

        // Rest of your login logic
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Retrieve the user using security
        $user = $this->getUser();
        $firstName = '';
        $lastName = '';

        if ($user) {
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
