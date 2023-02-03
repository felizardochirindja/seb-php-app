<?php

namespace Seb\Adapters\Repo\Ticket\Interfaces;

use DateTimeInterface as DateTime;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusEnum as TicketStatus;

interface TicketRepository
{
    public function createTicket(DateTime $emitionMoment, int $code, TicketStatus $status): array;
    public function readLastInsertedTicket(): array;
    public function readFirstTicketByStatus(TicketStatus $ticketStatus): array;
    public function updateTicketStatus(int $ticketId, TicketStatus $ticketStatus): bool;
    public function readTicketsByStatus(TicketStatus $ticketStatus): array;
}
