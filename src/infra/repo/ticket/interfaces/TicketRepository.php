<?php

namespace Seb\Infra\Repo\Ticket\Interfaces;

use DateTimeInterface as DateTime;

interface TicketRepository
{
    public function createTicket(DateTime $emitionMoment, int $code, string $status): array;
}
