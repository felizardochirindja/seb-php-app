<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Dompdf\Dompdf;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Seb\Adapters\DB\PDO\MySQL\MySQLPDOConnector;
use Seb\Adapters\DB\PDO\PDOConnectable as PDOConnector;
use Seb\Adapters\Libs\Ticket\PDFGenerator\DomPDF\DomPDFTicketGeneratorAdapter;
use Seb\Adapters\Libs\Ticket\PDFGenerator\TicketPDFGeneratable as TicketPDFGenerator;
use Seb\Adapters\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Adapters\Repo\Balcony\MySQL\PDOBalconyRepository;
use Seb\Adapters\Repo\Service\Interfaces\ServiceRepository;
use Seb\Adapters\Repo\Service\MySQL\PDOServiceRepository;
use Seb\Adapters\Repo\Ticket\Interfaces\TicketRepository;
use Seb\Adapters\Repo\Ticket\MySQL\PDOTicketRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        // adapters
        TicketPDFGenerator::class => new DomPDFTicketGeneratorAdapter(new Dompdf),
        LoggerInterface::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            
            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        // database
        PDOConnector::class => DI\create(MySQLPDOConnector::class)
            ->constructor('mysql:host=localhost;dbname=seb', 'root', ''),
           
        // repositories
        TicketRepository::class => function(ContainerInterface $container) {
            return new PDOTicketRepository($container->get(PDOConnector::class)->getConnection());
        },
        BalconyRepository::class => function(ContainerInterface $container) {
            return new PDOBalconyRepository($container->get(PDOConnector::class)->getConnection());
        },
        ServiceRepository::class => function(ContainerInterface $container) {
            return new PDOServiceRepository($container->get(PDOConnector::class)->getConnection());
        },
    ]);
};

