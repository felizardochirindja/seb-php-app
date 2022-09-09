<?php

namespace Seb\App\Ticket\EmitTicket;

use Seb\App\Ticket\EmitTicket\DTO\EmitTicketOutput;
use Seb\Enterprise\Ticket\Entities\TicketEntity;
use Seb\Infra\Repo\Ticket\Interfaces\CreateTicketRepository;

final class EmitTicketUseCase
{
    public function __construct(
        private CreateTicketRepository $repository,
        private TicketEntity $ticket,
    ) {}

    public function execute(): EmitTicketOutput
    {
        $ticket = $this->repository->createTicket(
            $this->ticket->getEmitionMoment(), 
            $this->ticket->getCode(),
            $this->ticket->getStatus(),
        );

        $emitTicketOutput = new EmitTicketOutput($ticket['emition_moment'], $ticket['code'], 'farmacia', 'seja paciente');
        return $emitTicketOutput;
    }
}
