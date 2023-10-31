<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\ChocolateShop;

class ChocolateShopVoter extends Voter
{
    public const VIEW = 'CHOCOLATE_SHOP_VIEW';
    public const CREATE = 'CHOCOLATE_SHOP_CREATE';
    public const EDIT = 'CHOCOLATE_SHOP_EDIT';
    public const DELETE = 'CHOCOLATE_SHOP_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::CREATE, self::EDIT, self::DELETE])
            && ($subject instanceof ChocolateShop || $subject === null);
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

        switch ($attribute) {
            case self::VIEW:
                return $this->canView();
            case self::CREATE:
            case self::EDIT:
            case self::DELETE:
                return $this->canModify();
        }

        return false;
    }

    private function canView(): bool
    {
        return true;
    }

    private function canModify(): bool
    {
        return false;
    }
}
