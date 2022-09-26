<?php

namespace Seb\App\Balcony\MarkTicketAsServed;

use DateTime;
use Exception;
use Seb\Enterprise\Balcony\Entities\BalconyEntity;
use Seb\Enterprise\Ticket\Entities\TicketEntity;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusValueObject as TicketStatus;
use Seb\Infra\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Infra\Repo\Ticket\Interfaces\TicketRepository;

final class MarkTicketAsServedUseCase
{
    public function __construct(
        private TicketRepository $ticketRepo,
        private BalconyRepository $balconyRepo,
        private BalconyEntity $balcony,
        private TicketEntity $ticket,
    ) {}

    public function execute(int $balconyNumber): bool
    {
        $actualBalconyStatus = $this->balconyRepo->readBalconyStatus($balconyNumber);

        if ($actualBalconyStatus === 'not in service') throw new Exception('balcony ' . $balconyNumber . ' is not serving any ticket');
        if ($actualBalconyStatus === 'inactive') throw new Exception('inactive ' . $balconyNumber . ' is not active');

        $balconyStatus = $this->balcony->setStatus('not in service')->getStatus();
        $this->balconyRepo->updateBalconyStatus($balconyNumber, $balconyStatus);

        $ticketStatus = $this->ticket->setStatus(new TicketStatus('in service'))->getStatus();
        $ticketId = $this->ticketRepo->readServiceByBalconyNumberAndStatus($balconyNumber, $ticketStatus)['id'];

        $this->balconyRepo->setEndServiceMoment($ticketId, $balconyNumber, new DateTime());

        $ticketStatus = $this->ticket->setStatus(new TicketStatus('attended'))->getStatus();
        $this->ticketRepo->updateTicketStatus($ticketId, $ticketStatus);

        return true;
    }
}
