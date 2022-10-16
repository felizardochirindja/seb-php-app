<?php

namespace Seb\Platform\Web\Ticket\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Seb\App\UseCases\Ticket\EmitTicket\EmitTicketUseCase;
use Seb\Platform\Web\BaseController;

final class TicketController extends BaseController
{
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
