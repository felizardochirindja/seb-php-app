<?php

namespace Seb\App\Balcony\ServeTicket;

use DateTimeImmutable;
use Exception;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusValueObject as BalconyStatus;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusValueObject as TicketStatus;
use Seb\Infra\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Infra\Repo\Ticket\Interfaces\TicketRepository;

final class ServeTicketUseCase
{
    public function __construct(
        private TicketRepository $ticketRepo,
        private BalconyRepository $balconyRepo,
    ) {}

    public function execute(int $balconyNumber): bool
    {
        $actualBalconyStatus = $this->balconyRepo->readBalconyStatus($balconyNumber);

        if ($actualBalconyStatus == 'in service') throw new Exception('balcony ' . $balconyNumber . ' is already in service');
        if ($actualBalconyStatus == 'inactive') throw new Exception('inactive ' . $balconyNumber . ' is not active');

        $firstPendingTicket = $this->ticketRepo->readFirstTicketByStatus(new TicketStatus('pending'));
        
        if (empty($firstPendingTicket)) {
            throw new Exception('no tickets in the queue', 1);
        }

        $startMoment = new DateTimeImmutable();
        $this->balconyRepo->createBalconyService($firstPendingTicket['id'], $balconyNumber, $startMoment);

        $balconyStatus = new BalconyStatus('in service');
        $this->balconyRepo->updateBalconyStatus($balconyNumber, $balconyStatus);

        $ticketStatus = new TicketStatus('in service');
        $this->ticketRepo->updateTicketStatus($firstPendingTicket['id'], $ticketStatus);

        return true;
    }
}
