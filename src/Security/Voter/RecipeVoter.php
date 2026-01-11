<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class RecipeVoter extends Voter
{
    public const EDIT = 'RECIPE_EDIT';
    public const VIEW = 'RECIPE_VIEW';
    public const DELETE = 'RECIPE_DELETE';
    public const CREATE = 'RECIPE_CREATE';
    public const LIST = 'RECIPE_LIST';

    protected function supports(string $attribute, mixed $subject): bool
    {

        return in_array($attribute,[self::LIST,self::CREATE]) || in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Recipe;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }


        $vote = match($attribute) {
            self::DELETE => $subject->getUser()->getId() === $user->getId(),
            self::CREATE => true,
            self::EDIT => $subject->getUser()->getId() === $user->getId(),
            self::LIST => true,
            self::VIEW => true,
        };


        return $vote;
    }
}
