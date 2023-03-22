<?php

declare(strict_types=1);

namespace PhpFidder\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316054847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create posts table';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<'SQL'
CREATE TABLE IF NOT EXISTS `post` (
    `id` binary(16) NOT NULL,
    `userId` binary(16) NOT NULL,
    `content` TEXT NOT NULL,
    `createdAt` datetime NOT NULL,
    `updatedAt` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    KEY `createdAt` (`createdAt`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
SQL;
        $this->connection->executeStatement($sql);
    }

    public function down(Schema $schema): void
    {
        $sql = <<<'SQL'
DROP TABLE IF EXISTS `post`
SQL;
        $this->connection->executeStatement($sql);
    }
}
