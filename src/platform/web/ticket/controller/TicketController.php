<?php

namespace Seb\Platform\Web\Ticket\Controller;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Seb\App\Ticket\EmitTicket\EmitTicketUseCase;
use Seb\Platform\Presenters\Interfaces\TicketPresenterInterface as TicketPresenter;

final class TicketController
{
    public function __construct(
        private Request $request,
        private Response $response,
        private EmitTicketUseCase $useCase,
    ) {}

    public function emitTicket(TicketPresenter $presenter)
    {
        $output = $this->useCase->execute();

        $body = $presenter->outputPDF([
            'pdf_code' => $output->pdfCode,
            'ticket_code' => $output->ticketCode,
        ]);

        header("Content-Type: application/pdf; charset=UTF-8");
        echo $body;
    }
}
