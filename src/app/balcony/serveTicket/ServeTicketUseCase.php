<?php

namespace Seb\App\Balcony\ServeTicket;

use DateTimeImmutable;
use Seb\Enterprise\Balcony\Entities\BalconyEntity;
use Seb\Enterprise\Ticket\Entities\TicketEntity;
use Seb\Infra\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Infra\Repo\Ticket\Interfaces\TicketRepository;

final class ServeTicketUseCase
{
    public function __construct(
        private TicketRepository $ticketRepo,
        private BalconyRepository $balconyRepo,
        private TicketEntity $ticket,
        private BalconyEntity $balcony,
    ) {}

    public function execute(int $balconyId): bool
    {
        $FirstpendingTicket = $this->ticketRepo->readFirstPendingTicketByStatus('pending');

        $startMoment = new DateTimeImmutable();
        $this->balconyRepo->createBalconyService($FirstpendingTicket['id'], $balconyId, $startMoment);

        $balconyStatus = $this->balcony->setStatus('in service')->getStatus();
        $this->balconyRepo->updateBalconyStatus($balconyId, $balconyStatus);

        $ticketStatus = $this->ticket->setStatus('in service')->getStatus();
        $this->ticketRepo->updateTicketStatus($FirstpendingTicket['id'], $ticketStatus);

        return true;
    }
}
