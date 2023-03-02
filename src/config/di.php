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
use Seb\Adapters\Repo\Ticket\Interfaces\TicketRepository;
use Seb\Adapters\Repo\Ticket\MySQL\PDOTicketRepository;
use Seb\App\UseCases\Ticket\EmitTicket\EmitTicketUseCase;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        TicketPDFGenerator::class => DI\create(DomPDFTicketGeneratorAdapter::class)->constructor(new Dompdf()),
        PDOConnector::class => DI\create(MySQLPDOConnector::class)->constructor('mysql:host=localhost;dbname=seb', 'root', ''),
        TicketRepository::class => function(ContainerInterface $container) {
            return new PDOTicketRepository($container->get(PDOConnector::class)->getConnection());
        },
        BalconyRepository::class => function(ContainerInterface $container) {
            return new PDOBalconyRepository($container->get(PDOConnector::class)->getConnection());
        },
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
        EmitTicketUseCase::class => function (ContainerInterface $container) {
            return new EmitTicketUseCase(
                $container->get(TicketRepository::class),
                $container->get(BalconyRepository::class),
                $container->get(TicketPDFGenerator::class),
                $container->get(LoggerInterface::class),
            );
        }
    ]);
};

