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

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW]) && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $loggedInUser = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$loggedInUser instanceof UserInterface) {
            return false;
        }

        /** @var User $userToManage */
        $userToManage = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($loggedInUser, $userToManage);
            case self::VIEW:
                return $this->canView($loggedInUser, $userToManage);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(UserInterface $loggedInUser, User $userToManage): bool
    {
        // Only super admin can edit (approve) admin users
        return in_array('ROLE_SUPER_ADMIN', $loggedInUser->getRoles())
            && in_array('ROLE_ADMIN', $userToManage->getRoles())
            && !$userToManage->isApproved(); // Assuming isApproved returns a boolean
    }

    private function canView(UserInterface $loggedInUser, User $userToManage): bool
    {
        // Currently, every logged-in user can view every user profile
        return true;
    }
}
