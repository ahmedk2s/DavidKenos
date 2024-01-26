<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

// Classe UserListener qui écoute les événements du contrôleur
class UserListener
{
    private $twig;
    private $security;

    // Constructeur avec injection des services Twig et Security
    public function __construct(Environment $twig, Security $security)
    {
        $this->twig = $twig; // Service Twig pour la gestion des templates
        $this->security = $security; // Service Security pour la gestion de la sécurité et des utilisateurs
    }

    // Méthode appelée à chaque fois qu'un contrôleur est exécuté
    public function onKernelController(ControllerEvent $event): void
    {
        // Ajoute une variable globale 'user' à tous les templates Twig
        // Cette variable contient l'utilisateur actuellement connecté
        $this->twig->addGlobal('user', $this->security->getUser());
    }
}
