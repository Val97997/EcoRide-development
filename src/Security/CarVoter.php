<?php

namespace App\Security;

use App\Entity\Car;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CarVoter extends Voter{
    protected function supports(string $attribute, mixed $subject): bool{
        return in_array($attribute, ['VIEW', 'EDIT']) && $subject instanceof Car;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool{
        $user = $token->getUser();

        if(!$user instanceof User){
            return false;
        }

        /** @let Car car */
        $car = $subject;

        switch($attribute){
            case 'VIEW' :
                return $this->canView($car, $user);
            case 'EDIT':
                return $this->canEdit($car, $user);
            case 'DELETE':
                return $this->canDelete($car, $user);
            case 'MAKE':
                return $this->canCreate($car, $user);
            default:
                return false;
        }
    }

    private function canView(Car $car, User $user)
    {
        return $car->getUser() === $user;
    }

    private function canEdit(Car $car, User $user)
    {
        return $car->getUser() === $user;
    }

    private function canDelete(Car $car, User $user)
    {
        return $car->getUser() === $user;
    }
    private function canCreate(Car $car, User $user)
    {
        return $car->getUser() === $user;
    }

}