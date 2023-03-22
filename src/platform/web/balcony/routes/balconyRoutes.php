<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Seb\App\UseCases\Balcony\MarkTicketAsServed\MarkTicketAsServedUseCase;
use Seb\App\UseCases\Balcony\ServeTicket\ServeTicketUseCase;
use Seb\Platform\Web\Balcony\Controller\BalconyController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/balcony', function(Group $group) use ($app) {
        $group->get('/{balconyId}/serve-ticket', function (Request $request, Response $response) use ($app) {
            /** @var ServeTicketUseCase $serveTicketUseCase */
            $serveTicketUseCase = $app->getContainer()->get(serveTicketUseCase::class);
            $controller = new BalconyController($request, $response);
            $response = $controller->serveTicket($serveTicketUseCase);
    
            return $response;
        });
        
        $group->get('/{balconyId}/mark-ticket-as-served', function (Request $request, Response $response) use ($app) {
            /** @var MarkTicketAsServedUseCase $useCase */
            $useCase = $app->getContainer()->get(markTicketAsServedUseCase::class);
            $controller = new BalconyController($request, $response);
            $response = $controller->markTicketAsServed($useCase);
            
            return $response;
        });
    });
};
