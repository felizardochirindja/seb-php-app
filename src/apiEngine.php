<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

$settings = require_once __DIR__ . '/config/settings.php';
$settings($containerBuilder);

$dependencies = require_once __DIR__ . '/config/di.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$middleware = require_once __DIR__ . '/config/middleware.php';
$middleware($app);

$ticketRoutes = require_once __DIR__ . '/platform/web/ticket/routes/ticketRoutesHandler.php';
$balconyRoutes = require_once __DIR__ . '/platform/web/balcony/routes/balconyRoutes.php';

$ticketRoutes($app);
$balconyRoutes($app);

$app->run();
