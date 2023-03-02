<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $container) {
    $container->addDefinitions([
        'settings' => function() {
            return [
                'name' => 'example slim application',
                'displayErrorDetails' => true, // false in production
                'logErrors' => true,
                'logErrorDetails' => true,
                
                'logger' => [
                    'name' => 'seb',
                    'path' => __DIR__ . '/../../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
            ];
        }
    ]);
};
