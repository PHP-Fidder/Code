<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';
use PhpFidder\Core\Components\Core\EventSubscriber;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$builder = new DI\ContainerBuilder();
$builder->addDefinitions(__DIR__.'/dependencies.php');
$container = $builder->build();

$subscriber = $container->get(EventSubscriber::class);
$subscriber->subscribeToEvents($container->get('listeners'));

require_once __DIR__.'/config/routes.php';
