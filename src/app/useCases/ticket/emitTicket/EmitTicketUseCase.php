<?php

namespace Seb\App\UseCases\Ticket\EmitTicket;

use DateTime;
use DateTimeImmutable;
use Exception;
use Seb\Adapters\Libs\Ticket\PDFGenerator\DTO\GenerateTicketPDFInput;
use Seb\Adapters\Libs\Ticket\PDFGenerator\TicketPDFGeneratable as TicketPDFGenerator;
use Seb\Adapters\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Adapters\Repo\Ticket\Interfaces\TicketRepository;
use Seb\App\UseCases\Ticket\EmitTicket\DTO\EmitTicketOutput;
use Seb\Enterprise\Ticket\Entities\TicketEntity;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusValueObject as TicketStatus;


final class EmitTicketUseCase
{
    public function __construct(
        private TicketRepository $ticketRepository,
        private BalconyRepository $balconyRepository,
        private TicketPDFGenerator $pdfGenerator,
    ) {}

    public function execute(): EmitTicketOutput
    {
        $isSomeBalconyActive = $this->balconyRepository->verifyActiveBalconies();
        if (!$isSomeBalconyActive) throw new Exception('there is no any active balcony');

        $ticketsFollowing = $this->ticketRepository->readTicketsByStatus(new TicketStatus('pending'));

        $ticket = $this->ticketRepository->readLastInsertedTicket();
        $ticketCode = empty($ticket) ? 99 : $ticket['code'];

        $ticket = new TicketEntity();
        $ticketStatus = new TicketStatus('pending');
        $ticket->setCode(++$ticketCode)
            ->setStatus($ticketStatus)
            ->setEmitionMoment(new DateTime());
            
        $insertedTicket = $this->ticketRepository->createTicket(
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
