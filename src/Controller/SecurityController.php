<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();
            if ($this->isGranted('ROLE_ADMIN')) {
                if (!$user->getIsApproved()) {
                    return $this->redirectToRoute('app_accueil');
                }
                // Redirection pour les admins approuvés
                return $this->redirectToRoute('app_admin');
            } elseif ($this->isGranted('ROLE_EMPLOYE')) {
                return $this->redirectToRoute('app_accueil');
            }
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = $this->getUser();
        $firstName = '';
        $lastName = '';

        if ($user) {
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
        }

        if ($request->query->get('admin_registered')) {
            $this->addFlash('notice', 'Votre compte administrateur est en attente de validation par un super administrateur.');
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