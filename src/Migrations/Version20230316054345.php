<?php

declare(strict_types=1);

namespace PhpFidder\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316054345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<'SQL'
CREATE TABLE IF NOT EXISTS `user` (
    `id` binary(16) NOT NULL,
    `username` varchar(20) NOT NULL,
    `passwordHash` varchar(64) NOT NULL,
    `email` varchar(255) NOT NULL,
    `createdAt` datetime NOT NULL,
    `updatedAt` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `username` (`username`),
    KEY `createdAt` (`createdAt`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
SQL;
        $this->connection->executeStatement($sql);
    }

    public function down(Schema $schema): void
    {
        $sql = <<<'SQL'
DROP TABLE IF EXISTS `user` 
SQL;
        $this->connection->executeStatement($sql);
    }
}
