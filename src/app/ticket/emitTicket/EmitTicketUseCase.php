<?php

namespace Seb\App\Ticket\EmitTicket;

use DateTime;
use DateTimeImmutable;
use Seb\Infra\Adapters\Ticket\PDFGenerator\TicketPDFGeneratable as TicketPDFGenerator;
use Seb\App\Ticket\EmitTicket\DTO\EmitTicketOutput;
use Seb\Enterprise\Ticket\Entities\TicketEntity;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusValueObject as TicketStatus;
use Seb\Infra\Adapters\Ticket\PDFGenerator\DTO\GenerateTicketPDFInput;
use Seb\Infra\Repo\Ticket\Interfaces\TicketRepository;

final class EmitTicketUseCase
{
    public function __construct(
        private TicketRepository $repository,
        private TicketPDFGenerator $pdfGenerator,
    ) {}

    public function execute(): EmitTicketOutput
    {
        $ticketsFollowing = $this->repository->readTicketsByStatus(new TicketStatus('pending'));

        $ticket = $this->repository->readLastInsertedTicket();
        $ticketCode = empty($ticket) ? 99 : $ticket['code'];

        $ticket = new TicketEntity();
        $ticketStatus = new TicketStatus('pending');
        $ticket->setCode(++$ticketCode)
            ->setStatus($ticketStatus)
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

        $pdfCode = $this->pdfGenerator->generate(
            new GenerateTicketPDFInput(
                $insertedTicket['code'],
                $insertedTicket['date'],
                $insertedTicket['time'],
                $insertedTicket['message'],
                count($ticketsFollowing),
            )
        );
        
        $emitTicketOutput = new EmitTicketOutput(
            $insertedTicket['code'],
            $pdfCode,
            $insertedTicket['date'],
            $insertedTicket['time']
        );
        return $emitTicketOutput;
    }
}
