<?php

namespace Seb\Infra\Repo\Ticket\Interfaces;

use DateTimeInterface as DateTime;

interface TicketRepository
{
    public function createTicket(DateTime $emitionMoment, int $code, string $status): array;
    public function readLastInsertedTicket(): array;
    public function readFirstPendingTicketByStatus(string $balconyStatus): array;
    public function updateTicketStatus(string $ticketId, string $ticketStatus): bool;
}
