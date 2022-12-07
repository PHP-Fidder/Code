<?php
require_once __DIR__ . '/vendor/autoload.php';

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use League\Route\RouteCollectionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

$builder = new \DI\ContainerBuilder();

$builder->addDefinitions(__DIR__.'/dependencies.php');

$container = $builder->build();
$router = $container->get(RouteCollectionInterface::class);
$request = $container->get(ServerRequestInterface::class);
$emitter = $container->get(EmitterInterface::class);
// map a route
$router->map('GET', '/', function (ServerRequestInterface $request): ResponseInterface {

    $response = new Laminas\Diactoros\Response;
    $response->getBody()->write('<h1>Hello, World!</h1>');
    return $response;
});

$response = $router->dispatch($request);

// send the response to the browser
$emitter->emit($response);