<?php

use Dompdf\Dompdf;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Seb\App\UseCases\Ticket\EmitTicket\EmitTicketUseCase;
use Seb\External\DB\PDO\MySQL\MySQLPDOConnector;
use Seb\External\Platform\Web\Ticket\Controller\TicketController;
use Seb\Infra\Adapters\Ticket\PDFGenerator\DomPDF\DomPDFTicketGeneratorAdapter;
use Seb\Infra\Repo\Balcony\MySQL\PDOBalconyRepository;
use Seb\Infra\Repo\Ticket\MySQL\PDOTicketRepository;
use Slim\App;

return function (App $app) {
    $app->get('/emit_ticket', function (RequestInterface $request, ResponseInterface $response, $args) {
        $mysqlConnector = new MySQLPDOConnector('mysql:host=localhost;dbname=seb', 'root', '');
    
        $domPDF = new Dompdf();
        $ticketPdfGen = new DomPDFTicketGeneratorAdapter($domPDF);
        $createTicketRepo = new PDOTicketRepository($mysqlConnector->getConnection());
        $balconyRepo = new PDOBalconyRepository($mysqlConnector->getConnection());
        $emitTicketUseCase = new EmitTicketUseCase($createTicketRepo, $balconyRepo, $ticketPdfGen);

        $controller = new TicketController($request, $response);
        $response = $controller->emitTicket($emitTicketUseCase);

        return $response;
    });
};
