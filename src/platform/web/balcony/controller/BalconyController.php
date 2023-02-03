<?php

namespace Seb\Platform\Web\Balcony\Controller;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Seb\App\UseCases\Balcony\MarkTicketAsServed\MarkTicketAsServedUseCase;
use Seb\App\UseCases\Balcony\ServeTicket\ServeTicketUseCase;
use Seb\Platform\Web\BaseController;

final class BalconyController extends BaseController
{
    public function serveTicket(
        ServeTicketUseCase $useCase,
    ): Response {
        $balconyNumber = (int) $this->request->getAttribute('balconyId');

        $useCase->execute($balconyNumber);

        $this->response
            ->getBody()
            ->write(json_encode([
                'status' => 'OK',
                'message' => 'serving ticket...',
            ]));
        
        $response = $this->response
            ->withStatus(StatusCodeInterface::STATUS_OK)
            ->withAddedHeader('Content-Type', 'application/json');

        return $response;
    }

    public function markTicketAsServed(MarkTicketAsServedUseCase $useCase): Response
    {
        $balconyNumber = (int) $this->request->getAttribute('balconyId');

        $useCase->execute($balconyNumber);

        $this->response
            ->getBody()
            ->write(json_encode([
                'status' => 'OK',
                'message' => 'ticket served!',
            ]));

        $response = $this->response
            ->withStatus(StatusCodeInterface::STATUS_OK)
            ->withAddedHeader('Content-Type', 'application/json');

        return $response;
    }
}
