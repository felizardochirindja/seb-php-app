<?php

namespace Seb\App\UseCases\Balcony\ServeTicket;

use DateTimeImmutable;
use Exception;
use Psr\Log\LoggerInterface;
use Seb\Adapters\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Adapters\Repo\Service\Interfaces\ServiceRepository;
use Seb\Adapters\Repo\Ticket\Interfaces\TicketRepository;
use Seb\App\UseCases\BaseUseCase;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusEnum as BalconyStatus;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusEnum as TicketStatus;

final class ServeTicketUseCase extends BaseUseCase
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
        
        if ($actualBalconyStatus === false) throw new Exception("balcony " . $balconyNumber . ' not found!');
        if ($actualBalconyStatus === BalconyStatus::InService) throw new Exception('balcony ' . $balconyNumber . ' is already in service');
        if ($actualBalconyStatus === BalconyStatus::Inactive) throw new Exception('balcony ' . $balconyNumber . ' is not active');

        $firstPendingTicket = $this->ticketRepo->readFirstFoundTicketByStatus(TicketStatus::Pending);
        if (empty($firstPendingTicket)) throw new Exception('no tickets in the queue', 1);

        $startMoment = new DateTimeImmutable();
        $this->serviceRepo->createBalconyService($firstPendingTicket['id'], $balconyNumber, $startMoment);
        $this->balconyRepo->updateBalconyStatus($balconyNumber, BalconyStatus::InService);
        $this->ticketRepo->updateTicketStatus($firstPendingTicket['id'], TicketStatus::InService);

        $this->logger->notice("balcony $balconyNumber serving ticket $firstPendingTicket[code]");

        return true;
    }
}
