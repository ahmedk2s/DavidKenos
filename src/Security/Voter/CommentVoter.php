<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Comment;

class CommentVoter extends Voter
{
    public const EDIT = 'COMMENT_EDIT';
    public const DELETE = 'COMMENT_DELETE';
    public const CREATE = 'COMMENT_CREATE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::CREATE])
            && $subject instanceof Comment;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Si l'utilisateur n'est pas connecté ou n'est pas un UserInterface, il ne peut rien faire
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Seul le super administrateur peut ajouter, éditer ou supprimer des commentaires
        return in_array('ROLE_SUPER_ADMIN', $user->getRoles());
    }
}
