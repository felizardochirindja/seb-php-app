<?php

declare(strict_types=1);

use DI\Container;
use Monolog\Logger;

return function (Container $container) {
    $container->set('settings', function() {
        return [
            'name' => 'example slim application',
            'displayErrorDetails' => true, // Should be set to false in production
            'logErrors' => true,
            'logErrorDetails' => true,
            
            'logger' => [
                'name' => 'seb',
                'path' => __DIR__ . '/../../logs/app.log',
                'level' => Logger::DEBUG,
            ],
        ];
    });
};
