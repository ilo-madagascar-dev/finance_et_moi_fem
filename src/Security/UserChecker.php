<?php
namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->getActive() == false) {
            throw new CustomUserMessageAuthenticationException('Votre compte est désactivé; probablement du fait d\'un désabonnent. Si ce n\'est pas le cas, veuillez contacter l\'administrateur du site.');
        }
        
        // user is deleted, show a generic Account Not Found message.
        /*if ($user->isDeleted()) {
            throw new AccountDeletedException();
        }*/
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
        /*if ($user->isExpired()) {
            throw new AccountExpiredException('...');
        }*/
    }
}