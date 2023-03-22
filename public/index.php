<?php
declare(strict_types=1);

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Psr\Http\Message\ServerRequestInterface;

error_reporting(E_ALL);
ini_set('display_errors','On');

require_once __DIR__.'/../bootstrap.php';

$request = $container->get(ServerRequestInterface::class);
$emitter = $container->get(EmitterInterface::class);

$response = $router->dispatch($request);

// send the response to the browser
$emitter->emit($response);