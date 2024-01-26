<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

class UserVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const VIEW = 'USER_VIEW';
    public const ACCESS_ADMIN = 'ACCESS_ADMIN';
    public const EDIT_PROFILE = 'EDIT_PROFILE';

    protected function supports(string $attribute, mixed $subject): bool
    {
       return in_array($attribute, [self::EDIT, self::VIEW, self::ACCESS_ADMIN, self::EDIT_PROFILE]) && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $loggedInUser = $token->getUser();

        if (!$loggedInUser instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($loggedInUser);
            case self::VIEW:
                return $this->canView();
            case self::ACCESS_ADMIN:
                return $this->canAccessAdmin($loggedInUser);
            case self::EDIT_PROFILE:
                return $this->canEditProfile($subject, $loggedInUser); // Ajout de la logique pour l'édition de profil
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(UserInterface $loggedInUser): bool
    {
        // Seuls les super administrateurs peuvent éditer
        return in_array('ROLE_SUPER_ADMIN', $loggedInUser->getRoles());
    }

    private function canView(User $subject, UserInterface $loggedInUser): bool
{
    // Les super administrateurs peuvent voir tous les utilisateurs
    if (in_array('ROLE_SUPER_ADMIN', $loggedInUser->getRoles())) {
        return true;
    }

    // Les administrateurs peuvent voir les utilisateurs de leur propre chocolaterie, mais pas le super administrateurs
    if (in_array('ROLE_ADMIN', $loggedInUser->getRoles())) {
        return $loggedInUser->getChocolateShop() === $subject->getChocolateShop() 
            && !in_array('ROLE_SUPER_ADMIN', $subject->getRoles());
    }

    return false;
}


    private function canAccessAdmin(UserInterface $loggedInUser): bool
    {
        // Les administrateurs et super administrateurs peuvent accéder à l'administration
        return in_array('ROLE_ADMIN', $loggedInUser->getRoles()) || in_array('ROLE_SUPER_ADMIN', $loggedInUser->getRoles());
    }

    private function canEditProfile(User $subject, UserInterface $loggedInUser): bool
    {
        // Un utilisateur peut éditer son propre profil
        return $subject->getId() === $loggedInUser->getId();
    }
}
