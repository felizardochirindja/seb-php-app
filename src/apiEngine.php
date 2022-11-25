<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response; 

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$settings = require_once __DIR__ . '/config/settings.php';
$settings($container);

AppFactory::setContainer($container);
$app = AppFactory::create();

$middleware = require_once __DIR__ . '/config/middleware.php';
$middleware($app);

$ticketRoutes = require_once __DIR__ . '/platform/web/ticket/routes/ticketRoutesHandler.php';
$balconyRoutes = require_once __DIR__ . '/platform/web/balcony/routes/balconyRoutesHandler.php';

$ticketRoutes($app);
$balconyRoutes($app);

$app->run();
