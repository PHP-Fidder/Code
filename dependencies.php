<?php

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\RouteCollectionInterface;
use League\Route\Router;
use Psr\Http\Message\ServerRequestInterface;

return [
    ServerRequestInterface::class => function () {
        return ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );
    },
    RouteCollectionInterface::class => function () {
        return new Router();
    },
    EmitterInterface::class => function () {
        return new SapiEmitter();
    }
];