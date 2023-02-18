<?php

namespace Seb\App\UseCases\Balcony\MarkTicketAsServed;

use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use Seb\Adapters\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Adapters\Repo\Service\Interfaces\ServiceRepository;
use Seb\Adapters\Repo\Ticket\Interfaces\TicketRepository;
use Seb\App\UseCases\BaseUseCase;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusEnum as BalconyStatus;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusEnum as TicketStatus;

final class MarkTicketAsServedUseCase extends BaseUseCase
{
    public function __construct(
        private TicketRepository $ticketRepo,
        private BalconyRepository $balconyRepo,
        private ServiceRepository $serviceRepo,
        protected LoggerInterface $logger,
    ) {
        parent::__construct($logger);
    }

    public function execute(int $balconyNumber): bool
    {
        $actualBalconyStatus = $this->balconyRepo->readBalconyStatus($balconyNumber);
        if ($actualBalconyStatus === BalconyStatus::NotInService) throw new Exception('balcony ' . $balconyNumber . ' is not serving any ticket');
        if ($actualBalconyStatus === BalconyStatus::Inactive) throw new Exception('balcony ' . $balconyNumber . ' is not active');

        $this->balconyRepo->updateBalconyStatus($balconyNumber, BalconyStatus::NotInService);

        $ticket = $this->serviceRepo->readServiceByBalconyNumberAndStatus($balconyNumber, TicketStatus::InService);
        if (empty($ticket)) throw new Exception('service with balcony ' . $balconyNumber . ' not found', 1);

        $ticketId = $ticket['id'];
        $this->serviceRepo->setEndServiceMoment($ticketId, $balconyNumber, new DateTime());
        $this->ticketRepo->updateTicketStatus($ticketId, TicketStatus::Attended);

        $this->logger->notice("balcony $balconyNumber served ticket $ticket[code]");

        return true;
    }
}
