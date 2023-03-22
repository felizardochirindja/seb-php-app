<?php

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Seb\App\UseCases\Ticket\EmitTicket\EmitTicketUseCase;
use Seb\Platform\Web\Ticket\Controller\TicketController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->group('/ticket', function(Group $group) use ($app) {
        $group->get('/emit_ticket', function (Request $request, Response $response) use ($app) {
            /** @var EmitTicketUseCase $emitTicketUseCase */
            $emitTicketUseCase = $app->getContainer()->get(EmitTicketUseCase::class);
            $controller = new TicketController($request, $response);
            $response = $controller->emitTicket($emitTicketUseCase);
    
            return $response;
        });
    });
};
