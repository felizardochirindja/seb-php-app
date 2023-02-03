<?php

namespace Seb\Adapters\Repo\Service\Interfaces;

use Seb\Enterprise\Ticket\ValueObjects\TicketStatusEnum as TicketStatus;
use DateTimeInterface;

interface ServiceRepository
{
    public function readServiceByBalconyNumberAndStatus(int $balconyNumber, TicketStatus $ticketStatus): array;
    public function createBalconyService(int $ticketId, int $balconyNumber, DateTimeInterface $startMoment): bool;
    public function setEndServiceMoment(int $ticketId,int $balconyNumber, DateTimeInterface $endMoment): bool;
}
