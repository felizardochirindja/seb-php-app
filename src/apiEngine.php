<?php

use DI\Container;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$settings = require_once __DIR__ . '/config/settings.php';
$settings($container);

AppFactory::setContainer($container);
$app = AppFactory::create();

$middleware = require_once __DIR__ . '/config/middleware.php';
$middleware($app);

$ticketRoutes = require_once __DIR__ . '/external/platform/web/ticket/routes/ticketRoutesHandler.php';
$ticketRoutes($app);

$app->run();
