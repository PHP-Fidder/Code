<?php

declare(strict_types=1);

namespace PhpFidder\Core\Repository;

use PhpFidder\Core\Entity\UserEntity;
use PhpFidder\Core\Hydrator\UserHydrator;

final class PDOUserRepository implements UserRepository
{
    private array $created = [];

    public function __construct(private readonly \PDO $connection, private readonly UserHydrator $userHydrator)
    {
    }

    public function add(UserEntity $userEntity): bool
    {
        $this->created[] = $userEntity;

        return true;
    }

    public function persist(): bool
    {
        $sql = [];
        $insertSQL = 'INSERT INTO user (id,username,passwordHash,email,createdAt) VALUES ';
        $insertData = [];

        /** @var UserEntity $user */
        foreach ($this->created as $index => $user) {
            $sql[] = sprintf(
                '(%s,%s,%s,%s,NOW())',
                ':id'.$index,
                ':username'.$index,
                ':passwordHash'.$index,
                ':email'.$index,
            );
            $insertData[':id'.$index] = $user->getId();
            $insertData[':username'.$index] = $user->getUsername();
            $insertData[':passwordHash'.$index] = $user->getPasswordHash();
            $insertData[':email'.$index] = $user->getEmail();
        }

        $insertSQL .= implode(',', $sql);

        $statement = $this->connection->prepare($insertSQL);
        $statement->execute($insertData);

        return true;
    }

    public function usernameExists(string $username): bool
    {
        $sql = 'SELECT 1 FROM user WHERE username=:username';
        $statement = $this->connection->prepare($sql);
        $statement->execute([':username' => $username]);

        return (bool) $statement->fetchColumn();
    }

    public function emailExists(string $email): bool
    {
        $sql = 'SELECT 1 FROM user WHERE email=:email';
        $statement = $this->connection->prepare($sql);
        $statement->execute([':email' => $email]);

        return (bool) $statement->fetchColumn();
    }

    public function findByUsername(string $username): UserEntity
    {
        $sql = 'SELECT id,username,passwordHash,email FROM user WHERE username=:username';
        $statement = $this->connection->prepare($sql);
        $statement->execute([':username' => $username]);
        $userArray = $statement->fetch();

        return $this->userHydrator->hydrate($userArray);
    }

    public function findById(string $userId): UserEntity
    {
        $sql = 'SELECT id,username,passwordHash,email FROM user WHERE id=:userId LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->execute([':userId' => $userId]);

        if (0 === $statement->rowCount()) {
            $message = sprintf('User %s not found', $userId);

            throw new \Exception($message);
        }

        $userArray = $statement->fetch();

        return $this->userHydrator->hydrate($userArray);
    }
}
