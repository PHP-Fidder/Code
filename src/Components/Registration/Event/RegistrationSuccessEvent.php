<?php
declare(strict_types=1);

namespace PhpFidder\Core\Components\Registration\Event;

use PhpFidder\Core\Entity\UserEntity;

final class RegistrationSuccessEvent
{
    public function __construct(private UserEntity $user){

    }

    public function getUser(): UserEntity
    {
        return $this->user;
    }
}