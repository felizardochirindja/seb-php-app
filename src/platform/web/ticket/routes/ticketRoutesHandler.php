<?php

use Dompdf\Dompdf;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Seb\Adapters\DB\PDO\MySQL\MySQLPDOConnector;
use Seb\Adapters\Libs\Ticket\PDFGenerator\DomPDF\DomPDFTicketGeneratorAdapter;
use Seb\Adapters\Repo\Balcony\MySQL\PDOBalconyRepository;
use Seb\Adapters\Repo\Ticket\MySQL\PDOTicketRepository;
use Seb\App\UseCases\Ticket\EmitTicket\EmitTicketUseCase;
use Seb\Platform\Web\Ticket\Controller\TicketController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/ticket', function(Group $group) {
        $group->get('/emit_ticket', function (Request $request, Response $response) {
            $mysqlConnector = new MySQLPDOConnector('mysql:host=localhost;dbname=seb', 'root', '');
    
            $domPDF = new Dompdf();
            $ticketPdfGen = new DomPDFTicketGeneratorAdapter($domPDF);
            $createTicketRepo = new PDOTicketRepository($mysqlConnector->getConnection());
            $balconyRepo = new PDOBalconyRepository($mysqlConnector->getConnection());

            $logger = new Logger('Seb');

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler(__DIR__ . '/../../../../../logs/app.log', Logger::DEBUG);
            $logger->pushHandler($handler);
    
            $emitTicketUseCase = new EmitTicketUseCase(
                $createTicketRepo,
                $balconyRepo,
                $ticketPdfGen,
                $logger
            );
    
            $controller = new TicketController($request, $response);
            $response = $controller->emitTicket($emitTicketUseCase);
    
            return $response;
        });
    });
};
