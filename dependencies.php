<?php
declare(strict_types=1);

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Event\EventDispatcher;
use League\Route\RouteCollectionInterface;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use PhpFidder\Core\Renderer\MustacheTemplateRenderer;
use PhpFidder\Core\Renderer\TemplateRendererInterface;
use PhpFidder\Core\Repository\PDOUserRepository;
use PhpFidder\Core\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

return [
    'templatePath' => __DIR__.'/templates',
    ServerRequestInterface::class => function () {
        return ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );
    },
    RouteCollectionInterface::class => function (ContainerInterface $container) {
        $strategy = (new ApplicationStrategy)->setContainer($container);
        return (new Router())->setStrategy($strategy);
    },
    EmitterInterface::class => function () {
        return new SapiEmitter();
    },
    EventDispatcherInterface::class => function(ContainerInterface $container){
        return new EventDispatcher();
    },
    TemplateRendererInterface::class => function(ContainerInterface $container)
    {
        $mustache = new Mustache_Engine([
            'loader' => new Mustache_Loader_FilesystemLoader($container->get('templatePath')),
            'partials_loader' => new Mustache_Loader_FilesystemLoader($container->get('templatePath')),
            'escape' => function($value){
                return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            }
        ]);
        return new MustacheTemplateRenderer($mustache);
    },
    PDO::class => function(ContainerInterface $container){
        $dsn = sprintf('mysql:host=%s;dbname=%s;port=%s;charset=%s',
            $_ENV['DB_HOST'],
            $_ENV['DB_NAME'],
            $_ENV['DB_PORT']??3306,
            $_ENV['DB_CHARSET']??'utf8'
        );
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        $pdo = new PDO($dsn,$username,$password);

        return $pdo;
    },
    UserRepository::class => function(ContainerInterface $container){
        $pdo = $container->get(PDO::class);
        return new PDOUserRepository($pdo);
    }
];