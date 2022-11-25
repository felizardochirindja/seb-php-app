<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Seb\Adapters\DB\PDO\MySQL\MySQLPDOConnector;
use Seb\Adapters\Repo\Balcony\MySQL\PDOBalconyRepository;
use Seb\Adapters\Repo\Ticket\MySQL\PDOTicketRepository;
use Seb\App\UseCases\Balcony\MarkTicketAsServed\MarkTicketAsServedUseCase;
use Seb\App\UseCases\Balcony\ServeTicket\ServeTicketUseCase;
use Seb\Platform\Web\Balcony\Controller\BalconyController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/balcony', function(Group $group) {
        $group->get('/{balconyId}/serve-ticket', function (Request $request, Response $response) {
            $mysqlConnector = new MySQLPDOConnector('mysql:host=localhost;dbname=seb', 'root', '');
            $ticketRepo = new PDOTicketRepository($mysqlConnector->getConnection());
            $balconyRepo = new PDOBalconyRepository($mysqlConnector->getConnection());
            $serveTicketUseCase = new ServeTicketUseCase($ticketRepo, $balconyRepo);
            $controller = new BalconyController($request, $response);
    
            $response = $controller->serveTicket($serveTicketUseCase);
    
            return $response;
        });
        
        $group->get('/{balconyId}/mark-ticket-as-served', function (Request $request, Response $response) {
            $mysqlConnector = new MySQLPDOConnector('mysql:host=localhost;dbname=seb', 'root', '');
            $ticketRepo = new PDOTicketRepository($mysqlConnector->getConnection());
            $balconyRepo = new PDOBalconyRepository($mysqlConnector->getConnection());
            $useCase = new MarkTicketAsServedUseCase($ticketRepo, $balconyRepo);
            $controller = new BalconyController($request, $response);
    
            $response = $controller->markTicketAsServed($useCase);
    
            return $response;
        });
    });
};
