<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Core;

interface PasswordHasherInterface
{
    public function hash(string $plainPassword): string;
    public function isValid(string $plainPassword, string $passwordHash): bool;
}
