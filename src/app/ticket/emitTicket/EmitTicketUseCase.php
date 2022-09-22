<?php

namespace Seb\App\Ticket\EmitTicket;

use DateTime;
use DateTimeImmutable;
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

        $ticketEmitionMoment = $insertedTicket['emition_moment'];
        unset($insertedTicket['emition_moment']);
        $ticketEmitionMoment = new DateTimeImmutable($ticketEmitionMoment);

        $insertedTicket['date'] = $ticketEmitionMoment->format('d/m/Y');
        $insertedTicket['time'] = $ticketEmitionMoment->format('H:i:s');
        $insertedTicket['message'] = 'obedeça a fila...';

        $emitTicketOutput = new EmitTicketOutput(
            $insertedTicket['code'],
            $pdfCode,
            $insertedTicket['date'],
            $insertedTicket['time']
        );
        return $emitTicketOutput;
    }
}
