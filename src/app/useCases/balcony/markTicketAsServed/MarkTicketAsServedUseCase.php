<?php

namespace Seb\App\UseCases\Balcony\MarkTicketAsServed;

use DateTime;
use Exception;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusValueObject as BalconyStatus;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusValueObject as TicketStatus;
use Seb\Infra\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Infra\Repo\Ticket\Interfaces\TicketRepository;

final class MarkTicketAsServedUseCase
{
    public function __construct(
        private TicketRepository $ticketRepo,
        private BalconyRepository $balconyRepo,
    ) {}

    public function execute(int $balconyNumber): bool
    {
        $actualBalconyStatus = $this->balconyRepo->readBalconyStatus($balconyNumber);

        if ($actualBalconyStatus === 'not in service') throw new Exception('balcony ' . $balconyNumber . ' is not serving any ticket');
        if ($actualBalconyStatus === 'inactive') throw new Exception('inactive ' . $balconyNumber . ' is not active');

        $balconyStatus = new BalconyStatus('not in service');
        $this->balconyRepo->updateBalconyStatus($balconyNumber, $balconyStatus);

        $ticketStatus = new TicketStatus('in service');
        $ticket = $this->ticketRepo->readServiceByBalconyNumberAndStatus($balconyNumber, $ticketStatus);

        if (empty($ticket)) {
            throw new Exception('service with balcony ' . $balconyNumber . ' not found', 1);
        }

        $ticketId = $ticket['id'];
        $this->balconyRepo->setEndServiceMoment($ticketId, $balconyNumber, new DateTime());

        $ticketStatus = new TicketStatus('attended');
        $this->ticketRepo->updateTicketStatus($ticketId, $ticketStatus);

        return true;
    }
}
