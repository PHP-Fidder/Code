<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Core;

abstract class AbstractValidator
{
    protected array $errors = [];

    abstract public function validate(ValidatorRequestInterface $request): array;

    public function isValid(ValidatorRequestInterface $request): bool
    {
        $this->errors = $this->validate($request);

        return 0 === count($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
