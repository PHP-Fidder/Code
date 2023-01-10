<?php
declare(strict_types=1);

namespace PhpFidder\Core\Repository;

use PDO;
use PhpFidder\Core\Entity\UserEntity;

final class PDOUserRepository implements UserRepository
{
    private array $created = [];
    public function __construct(private readonly PDO $connection){

    }
    public function add(UserEntity $userEntity): bool
    {
        $this->created[]=$userEntity;
        return true;
    }

    public function persist(): bool
    {
        $sql = [];
        $insertSQL = 'INSERT INTO user (id,username,passwordHash,email,createdAt) VALUES ';
        $insertData = [];
        /** @var UserEntity $user */
        foreach($this->created as $index =>  $user){
            $sql[] = sprintf('(%s,%s,%s,%s,NOW())',
                ':id'.$index,
                ':username'.$index,
                ':passwordHash'.$index,
                ':email'.$index,
            );
            $insertData[ ':id'.$index ] = $user->getId();
            $insertData[ ':username'.$index ] = $user->getUsername();
            $insertData[ ':passwordHash'.$index ] = $user->getPasswordHash();
            $insertData[ ':email'.$index ] = $user->getEmail();
        }

        $insertSQL .= implode(',',$sql);

        $statement = $this->connection->prepare($insertSQL);
        $statement->execute($insertData);


        return true;
    }

}