<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Core;

abstract class AbstractValidator
{
    private array $errors = [];

    abstract public function validate(ValidatorRequestInterface $request): array;
    public function isValid(ValidatorRequestInterface $request): bool
    {
        $this->errors = $this->validate($request);
        return count($this->errors) === 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
