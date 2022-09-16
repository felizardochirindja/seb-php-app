<?php

namespace Seb\App\Ticket\EmitTicket;

use Seb\Infra\Adapters\Ticket\PDFGenerator\TicketPDFGeneratable as TicketPDFGenerator;
use Seb\App\Ticket\EmitTicket\DTO\EmitTicketOutput;
use Seb\Enterprise\Ticket\Entities\TicketEntity;
use Seb\Infra\Repo\Ticket\Interfaces\CreateTicketRepository;

final class EmitTicketUseCase
{
    public function __construct(
        private CreateTicketRepository $repository,
        private TicketEntity $ticket,
        private TicketPDFGenerator $pdfGenerator,
    ) {}

    public function execute(): EmitTicketOutput
    {
        $ticket = $this->repository->createTicket(
            $this->ticket->getEmitionMoment(),
            $this->ticket->getCode(),
            $this->ticket->getStatus(),
        );

        $pdfCode = $this->pdfGenerator->generate($ticket);
        $emitTicketOutput = new EmitTicketOutput($ticket['code'], $pdfCode);
        return $emitTicketOutput;
    }
}
