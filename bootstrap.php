<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use League\Route\RouteCollectionInterface;
use PhpFidder\Core\Components\Login\Action\Login;
use PhpFidder\Core\Components\Registration\Action\Register;
use PhpFidder\Core\Renderer\TemplateRendererInterface;
use PhpFidder\Core\Renderer\TemplateRendererMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$builder = new \DI\ContainerBuilder();

$builder->addDefinitions(__DIR__.'/dependencies.php');

$container = $builder->build();
/** @var \League\Route\Router $router */
$router = $container->get(RouteCollectionInterface::class);
$request = $container->get(ServerRequestInterface::class);
$emitter = $container->get(EmitterInterface::class);

$router->middleware($container->get(TemplateRendererMiddleware::class));

// map a route
$router->map('GET', '/', function (ServerRequestInterface $request) use($container): ResponseInterface {
    $renderer = $container->get(TemplateRendererInterface::class);

    $body = $renderer->render('index',['test'=>'Hello World 2']);
    $response = new Laminas\Diactoros\Response;
    $response->getBody()->write($body);
    return $response;
});

$router->map('GET','/account/create', Register::class);
$router->map('POST','/account/create', Register::class);
$router->map('GET','/account/login', Login::class);
$router->map('POST','/account/login', Login::class);


$response = $router->dispatch($request);

// send the response to the browser
$emitter->emit($response);