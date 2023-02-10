<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Landing\Event;

use PhpFidder\Core\Components\Landing\Request\IndexRequest;

final class IndexEvent
{
    public function __construct(private string $welcomeMessage)
    {
    }
    public function setMessage(string $message): void
    {
        $this->welcomeMessage = $message;
    }

    /**
     * @return string
     */
    public function getWelcomeMessage(): string
    {
        return $this->welcomeMessage;
    }
}
