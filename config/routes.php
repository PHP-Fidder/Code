<?php

declare(strict_types=1);

use League\Route\RouteCollectionInterface;
use League\Route\RouteGroup;
use PhpFidder\Core\Components\Auth\Action\Login;
use PhpFidder\Core\Components\Auth\Action\Logout;
use PhpFidder\Core\Components\Home\Action\Home;
use PhpFidder\Core\Components\Landing\Action\Index;
use PhpFidder\Core\Components\Posts\Action\Create;
use PhpFidder\Core\Components\Registration\Action\Register;

/** @var \League\Route\Router $router */
$router = $container->get(RouteCollectionInterface::class);
$router->middlewares($container->get('middlewares'));

// map a route
$router->map('GET', '/', Index::class);
$router->map('GET', '/home', Home::class);

$router->group('/account', function (RouteGroup $route) {
    $route->map('GET', '/create', Register::class);
    $route->map('POST', '/create', Register::class);
    $route->map('GET', '/login', Login::class);
    $route->map('POST', '/login', Login::class);
    $route->map('GET', '/logout', Logout::class);
});
$router->group('/posts', function (RouteGroup $route) {
    $route->map('POST', '/create', Create::class)->setName('posts.create');
});
