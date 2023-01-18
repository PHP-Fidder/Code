<?php
declare(strict_types=1);

namespace PhpFidder\Core\Hydrator;

use PhpFidder\Core\Entity\UserEntity;
use Ramsey\Uuid\Uuid;

final class UserHydrator
{
    public function hydrate(array $data):UserEntity
    {
        $id = $data['id'] ?? Uuid::uuid7()->getBytes();
        $password = password_hash($data['password'],PASSWORD_DEFAULT);
        return new UserEntity($id ,$data['username'],$password,$data['email']);
    }
}