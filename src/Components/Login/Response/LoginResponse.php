<?php

declare(strict_types=1);

namespace PhpFidder\Core\Components\Login\Response;

use Laminas\Diactoros\Response;
use PhpFidder\Core\Components\Login\Request\LoginRequest;
use PhpFidder\Core\Renderer\RenderAwareInterface;
use Psr\Http\Message\ResponseInterface;

final class LoginResponse extends Response implements RenderAwareInterface
{
    public readonly string $username;
    public readonly string $password;
    public readonly array $errors;
    public function getTemplateName(): string
    {
        return 'login';
    }
    public function __construct(LoginRequest $request)
    {
        parent::__construct();
        $this->username = $request->getUsername();
        $this->password = $request->getPassword();
        $this->errors = $request->getErrors();
    }
}
