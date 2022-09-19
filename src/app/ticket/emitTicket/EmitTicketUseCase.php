<?php

namespace Seb\App\Ticket\EmitTicket;

use DateTime;
use Seb\Infra\Adapters\Ticket\PDFGenerator\TicketPDFGeneratable as TicketPDFGenerator;
use Seb\App\Ticket\EmitTicket\DTO\EmitTicketOutput;
use Seb\Enterprise\Ticket\Entities\TicketEntity;
use Seb\Infra\Repo\Ticket\Interfaces\TicketRepository;

final class EmitTicketUseCase
{
    public function __construct(
        private TicketRepository $repository,
        private TicketEntity $ticket,
        private TicketPDFGenerator $pdfGenerator,
    ) {}

    public function execute(): EmitTicketOutput
    {
        $ticketCode = (int) $this->repository->readLastInsertedTicket()['code'];
        $this->ticket->setCode(++$ticketCode)
            ->setStatus('pending')
            ->setEmitionMoment(new DateTime());

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
