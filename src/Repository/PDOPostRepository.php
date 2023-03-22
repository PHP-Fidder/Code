<?php

declare(strict_types=1);

namespace PhpFidder\Core\Repository;

use PhpFidder\Core\Entity\PostEntity;

final class PDOPostRepository implements PostRepository
{
    private array $created;

    public function __construct(private readonly \PDO $connection)
    {
    }

    public function add(PostEntity $post): bool
    {
        $this->created[] = $post;

        return true;
    }

    public function persist(): bool
    {
        $sql = [];
        $insertSQL = 'INSERT INTO post (id,userId,content,createdAt) VALUES ';
        $insertData = [];

        /** @var PostEntity $post */
        foreach ($this->created as $index => $post) {
            $sql[] = sprintf(
                '(%s,%s,%s,NOW())',
                ':id'.$index,
                ':userId'.$index,
                ':content'.$index,
            );
            $insertData[':id'.$index] = $post->getId();
            $insertData[':content'.$index] = $post->getContent();
            $insertData[':userId'.$index] = $post->getUserId();
        }

        $insertSQL .= implode(',', $sql);

        $statement = $this->connection->prepare($insertSQL);

        return $statement->execute($insertData);
    }
}
