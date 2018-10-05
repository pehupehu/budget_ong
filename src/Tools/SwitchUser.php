<?php

namespace App\Tools;

use Symfony\Component\Security\Core\Role\SwitchUserRole;
use Symfony\Component\Security\Core\Security;

class SwitchUser
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function someMethod()
    {
        $impersonatorUser = null;

        if ($this->security->isGranted('ROLE_PREVIOUS_ADMIN')) {
            foreach ($this->security->getToken()->getRoles() as $role) {
                if ($role instanceof SwitchUserRole) {
                    $impersonatorUser = $role->getSource()->getUser();
                    break;
                }
            }
        }

        return $impersonatorUser;
    }
}
