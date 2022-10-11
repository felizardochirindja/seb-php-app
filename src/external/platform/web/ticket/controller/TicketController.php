<?php

namespace Seb\External\Platform\Web\Ticket\Controller;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Seb\App\UseCases\Ticket\EmitTicket\EmitTicketUseCase;

final class TicketController
{
    public function __construct(
        private Request $request,
        private Response $response,
    ) {}

    public function emitTicket(
        EmitTicketUseCase $useCase,
    ): Response
    {
        $output = $useCase->execute();
        $this->response
            ->getBody()
            ->write($output->pdfCode);
        
        header('content-type: application/pdf');
        http_response_code(200);

        return $this->response;
    }
}
