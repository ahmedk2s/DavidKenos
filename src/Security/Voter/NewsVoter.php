<?php

namespace App\Security\Voter;

use App\Entity\News;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class NewsVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof News;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // you know $subject is a News object, thanks to `supports()`
        /** @var News $news */
        $news = $subject;

        switch ($attribute) {
            case 'EDIT':
            case 'DELETE':
                if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
                    return true;
                }
                break;
        }

        return false;
    }
}
