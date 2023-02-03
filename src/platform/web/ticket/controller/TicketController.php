<?php

namespace Seb\Platform\Web\Ticket\Controller;

use Fig\Http\Message\StatusCodeInterface;
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

        $response = $this->response
            ->withStatus(StatusCodeInterface::STATUS_OK)
            ->withAddedHeader('Content-Type', 'application/pdf');

        return $response;
    }
}
