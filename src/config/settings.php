<?php

declare(strict_types=1);

use DI\Container;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function (Container $container) {
    $container->set('settings', function() {
        return [
            'name' => 'example slim application',
            'displayErrorDetails' => true, // Should be set to false in production
            'logErrors' => true,
            'logErrorDetails' => true,
            // 'logger' => [
            //     'name' => 'slim-app',
            //     'path' => __DIR__ . '/../logs/app.log',
            //     'level' => Logger::DEBUG,
            // ],
        ];
    });

    $container->set('', function() {
        return function (Request $request, Response $response, array $methods) {
            return $response->withStatus(405)
                ->withHeader('Allow', implode(', ', $methods))
                ->withHeader('Content-type', 'aplication/json')
                ->getBody()
                ->write([
                    "status" => false,
                    "message" => "Method Not Allowed",
                    'tip' => 'Alowed HTTP methods: ' . implode(', ', $methods),
                ]);
        };
    });
};
