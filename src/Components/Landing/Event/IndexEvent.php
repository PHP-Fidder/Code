<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Landing\Event;

final class IndexEvent
{
    public function __construct(private string $welcomeMessage)
    {
    }

    public function setMessage(string $message): void
    {
        $this->welcomeMessage = $message;
    }

    public function getWelcomeMessage(): string
    {
        return $this->welcomeMessage;
    }
}
