<?php

namespace Seb\App\Balcony\ServeTicket;

use DateTimeImmutable;
use Exception;
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

    public function execute(int $balconyNumber): bool
    {
        $actualBalconyStatus = $this->balconyRepo->readBalconyStatus($balconyNumber);

        if ($actualBalconyStatus === 'in service') throw new Exception('balcony ' . $balconyNumber . ' is already in service');
        if ($actualBalconyStatus === 'inactive') throw new Exception('inactive ' . $balconyNumber . ' is not active');

        $firstPendingTicket = $this->ticketRepo->readFirstTicketByStatus('pending');

        $startMoment = new DateTimeImmutable();
        $this->balconyRepo->createBalconyService($firstPendingTicket['id'], $balconyNumber, $startMoment);

        $balconyStatus = $this->balcony->setStatus('in service')->getStatus();
        $this->balconyRepo->updateBalconyStatus($balconyNumber, $balconyStatus);

        $ticketStatus = $this->ticket->setStatus('in service')->getStatus();
        $this->ticketRepo->updateTicketStatus($firstPendingTicket['id'], $ticketStatus);

        return true;
    }
}
