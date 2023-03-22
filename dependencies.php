<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\Session\Config\SessionConfig;
use Laminas\Session\Container;
use Laminas\Session\ManagerInterface;
use Laminas\Session\SessionManager;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Storage\StorageInterface;
use League\Event\EventDispatcher;
use League\Route\RouteCollectionInterface;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use PhpFidder\Core\Components\Auth\Middleware\AuthenticationMiddleware;
use PhpFidder\Core\Components\Core\EventSubscriber;
use PhpFidder\Core\Components\Core\ExceptionMiddleware;
use PhpFidder\Core\Components\Core\NativePasswordHasher;
use PhpFidder\Core\Components\Core\PasswordHasherInterface;
use PhpFidder\Core\Components\Landing\Event\IndexListener;
use PhpFidder\Core\Renderer\Helpers\MustacheHelperInterface;
use PhpFidder\Core\Renderer\Helpers\PathToRouteHelper;
use PhpFidder\Core\Renderer\MustacheTemplateRenderer;
use PhpFidder\Core\Renderer\TemplateRendererInterface;
use PhpFidder\Core\Renderer\TemplateRendererMiddleware;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

return [
    // Template
    'templatePath' => __DIR__.'/templates',
    'mustache.helperClasses' => [
        DI\get(PathToRouteHelper::class),
    ],
    'mustache.helpers' => function (ContainerInterface $container) {
        /** @var MustacheHelperInterface[] $helperClasses */
        $helperClasses = $container->get('mustache.helperClasses');
        $helpers = [];
        foreach ($helperClasses as $helper) {
            $helpers[$helper->getName()] = $helper;
        }

        return $helpers;
    },
    'mustache.config' => [
        'loader' => Di\create(Mustache_Loader_FilesystemLoader::class)
            ->constructor(DI\get('templatePath')),
        'partials_loader' => Di\create(Mustache_Loader_FilesystemLoader::class)
            ->constructor(DI\get('templatePath')),
        'escape' => DI\value(function (string $value) {
            return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        }),
        'helpers' => DI\get('mustache.helpers'),
    ],
    'mustache' => DI\create(Mustache_Engine::class)
        ->constructor(DI\get('mustache.config')),
    TemplateRendererInterface::class => DI\create(MustacheTemplateRenderer::class)
        ->constructor(DI\get('mustache')),
    // DB
    'db.host' => DI\env('DB_HOST'),
    'db.name' => DI\env('DB_NAME'),
    'db.port' => DI\env('DB_PORT', 3306),
    'db.charset' => DI\env('DB_CHARSET', 'utf8'),
    'db.username' => DI\env('DB_USERNAME'),
    'db.password' => DI\env('DB_PASSWORD'),
    PDO::class => DI\create(PDO::class)->constructor(
        DI\string('mysql:host={db.host};dbname={db.name};port={db.port};charset={db.charset}'),
        DI\get('db.username'),
        DI\get('db.password'),
    ),
    // Map all repository interfaces to repository classes
    'PhpFidder\Core\Repository\*Repository' => DI\autowire('PhpFidder\Core\Repository\PDO*Repository'),
    // Events
    'listeners' => [
        DI\get(IndexListener::class),
    ],
    EventDispatcherInterface::class => DI\create(EventDispatcher::class),
    EventSubscriber::class => DI\create(EventSubscriber::class)->constructor(
        DI\get(EventDispatcherInterface::class)
    ),
    // Session
    'session.config' => DI\create(SessionConfig::class),
    StorageInterface::class => DI\create(SessionArrayStorage::class),
    ManagerInterface::class => DI\create(SessionManager::class)
        ->constructor(
            DI\get('session.config'),
            DI\get(StorageInterface::class)
        ),
    Container::class => DI\create(Container::class)
        ->constructor(
            'default',
            DI\get(ManagerInterface::class),
        ),
    // Migration
    'migration.config' => function (ContainerInterface $container) {
        $params = [
            'table_storage' => [
                'table_name' => 'doctrine_migration_versions',
                'version_column_name' => 'version',
                'version_column_length' => 191,
                'executed_at_column_name' => 'executed_at',
                'execution_time_column_name' => 'execution_time',
            ],

            'migrations_paths' => [
                'PhpFidder\Migrations' => __DIR__.'/src/Migrations',
            ],
            'all_or_nothing' => true,
            'transactional' => true,
            'check_database_platform' => true,
            'organize_migrations' => 'none',
            'connection' => null,
            'em' => null,
        ];

        return new ConfigurationArray($params);
    },
    'migration.connection' => function (ContainerInterface $container) {
        $params = [
            'driver' => 'pdo_mysql',
            'host' => $container->get('db.host'),
            'user' => $container->get('db.username'),
            'password' => $container->get('db.password'),
            'dbname' => $container->get('db.name'),
        ];

        return new ExistingConnection(DriverManager::getConnection($params));
    },
    'migration' => function (ContainerInterface $container) {
        return DependencyFactory::fromConnection($container->get('migration.config'), $container->get('migration.connection'));
    },
    // Framework
    ServerRequestInterface::class => function () {
        return ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    },
    'middlewares' => [
        DI\get(TemplateRendererMiddleware::class),
        DI\get(ExceptionMiddleware::class),
        DI\get(AuthenticationMiddleware::class),
    ],

    RouteCollectionInterface::class => function (ContainerInterface $container) {
        $strategy = new ApplicationStrategy();
        $strategy->setContainer($container);

        return (new Router())->setStrategy($strategy);
    },
    EmitterInterface::class => Di\create(SapiEmitter::class),
    // Services
    PasswordHasherInterface::class => DI\create(NativePasswordHasher::class),
];
