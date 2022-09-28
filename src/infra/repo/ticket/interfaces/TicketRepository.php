<?php

namespace Seb\Infra\Repo\Ticket\Interfaces;

use DateTimeInterface as DateTime;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusValueObject as TicketStatus;

interface TicketRepository
{
    public function createTicket(DateTime $emitionMoment, int $code, TicketStatus $status): array;
    public function readLastInsertedTicket(): array;
    public function readFirstTicketByStatus(TicketStatus $ticketStatus): array;
    public function updateTicketStatus(int $ticketId, TicketStatus $ticketStatus): bool;
    public function readServiceByBalconyNumberAndStatus(int $balconyNumber, TicketStatus $ticketStatus): array;
    public function readTicketsByStatus(TicketStatus $ticketStatus): array;
}
