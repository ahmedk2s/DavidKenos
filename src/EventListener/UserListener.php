<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class UserListener
{
    private $twig;
    private $security;

    public function __construct(Environment $twig, Security $security)
    {
        $this->twig = $twig; 
        $this->security = $security; 
    }

    
    public function onKernelController(ControllerEvent $event): void
    {
        
        $this->twig->addGlobal('user', $this->security->getUser());
    }
}
