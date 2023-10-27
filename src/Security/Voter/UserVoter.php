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

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::ACCESS_ADMIN]) && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $loggedInUser = $token->getUser();

        if (!$loggedInUser instanceof UserInterface) {
            return false;
        }

        // Autoriser le super administrateur à tout faire
        if (in_array('ROLE_SUPER_ADMIN', $loggedInUser->getRoles())) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($loggedInUser, $subject);
            case self::VIEW:
                return $this->canView();
            case self::ACCESS_ADMIN:
                return $this->canAccessAdmin($loggedInUser);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(UserInterface $loggedInUser, User $userToManage): bool
    {
        return in_array('ROLE_ADMIN', $loggedInUser->getRoles()) && !$userToManage->getIsApproved();
    }

    private function canView(): bool
    {
        return true;
    }

   private function canAccessAdmin(UserInterface $loggedInUser): bool
{
    return in_array('ROLE_ADMIN', $loggedInUser->getRoles()) && $loggedInUser->getIsApproved();
}
}




