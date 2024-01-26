<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Category;

class CategoryVoter extends Voter
{
    public const VIEW = 'CATEGORY_VIEW';
    public const CREATE = 'CATEGORY_CREATE';
    public const EDIT = 'CATEGORY_EDIT';
    public const DELETE = 'CATEGORY_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::CREATE, self::EDIT, self::DELETE])
            && ($subject instanceof Category || $subject === null);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            return true;
        }

        if ($attribute === self::VIEW) {
            return true;
        }

        return false;
    }
}
