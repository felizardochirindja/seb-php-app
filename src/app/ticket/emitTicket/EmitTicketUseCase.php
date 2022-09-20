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
        private TicketPDFGenerator $pdfGenerator,
    ) {}

    public function execute(): EmitTicketOutput
    {
        $ticketCode = (int) $this->repository->readLastInsertedTicket()['code'];

        $ticket = new TicketEntity();
        $ticket->setCode(++$ticketCode)
            ->setStatus('pending')
            ->setEmitionMoment(new DateTime());
            
        $insertedTicket = $this->repository->createTicket(
            $ticket->getEmitionMoment(),
            $ticket->getCode(),
            $ticket->getStatus(),
        );

        $pdfCode = $this->pdfGenerator->generate($insertedTicket);
        $emitTicketOutput = new EmitTicketOutput($insertedTicket['code'], $pdfCode);
        return $emitTicketOutput;
    }
}
