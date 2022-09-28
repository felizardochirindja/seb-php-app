<?php

namespace Seb\Platform\Web\Ticket\Controller;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Seb\App\Ticket\EmitTicket\EmitTicketUseCase;

final class TicketController
{
    public function __construct(
        private Request $request,
        private Response $response,
    ) {}

    public function emitTicket(
        EmitTicketUseCase $useCase,
    ) {
        $output = $useCase->execute();

        header("Content-Type: application/pdf; charset=UTF-8");
        echo $output->pdfCode;
    }
}
