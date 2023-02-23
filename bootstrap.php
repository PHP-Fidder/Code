<?php

declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use League\Route\RouteCollectionInterface;
use PhpFidder\Core\Components\Auth\Action\Logout;
use PhpFidder\Core\Components\Auth\Middleware\AuthenticationMiddleware;
use PhpFidder\Core\Components\Core\EventSubscriber;
use PhpFidder\Core\Components\Core\ExceptionMiddleware;
use PhpFidder\Core\Components\Home\Action\Home;
use PhpFidder\Core\Components\Landing\Action\Index;
use PhpFidder\Core\Components\Auth\Action\Login;
use PhpFidder\Core\Components\Registration\Action\Register;
use PhpFidder\Core\Renderer\TemplateRendererMiddleware;
use Psr\Http\Message\ServerRequestInterface;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$builder = new \DI\ContainerBuilder();

$builder->addDefinitions(__DIR__ . '/dependencies.php');

$container = $builder->build();
/** @var \League\Route\Router $router */
$router = $container->get(RouteCollectionInterface::class);
$request = $container->get(ServerRequestInterface::class);
$emitter = $container->get(EmitterInterface::class);

$router->middleware($container->get(TemplateRendererMiddleware::class));
$router->middleware($container->get(ExceptionMiddleware::class));
$router->middleware($container->get(AuthenticationMiddleware::class));


$subscriber = $container->get(EventSubscriber::class);
$subscriber->subscribeToEvents();



// map a route
$router->map('GET', '/', Index::class);

$router->map('GET', '/account/create', Register::class);
$router->map('POST', '/account/create', Register::class);
$router->map('GET', '/account/login', Login::class);
$router->map('POST', '/account/login', Login::class);
$router->map('GET', '/account/logout', Logout::class);
$router->map('GET', '/home', Home::class);

$response = $router->dispatch($request);

// send the response to the browser
$emitter->emit($response);
