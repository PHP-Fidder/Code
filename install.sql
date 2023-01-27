
-- Exportiere Datenbank Struktur für fidder
CREATE DATABASE IF NOT EXISTS `fidder` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `fidder`;

-- Exportiere Struktur von Tabelle fidder.user
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