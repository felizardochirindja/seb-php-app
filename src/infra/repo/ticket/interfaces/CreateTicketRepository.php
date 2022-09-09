<?php

namespace Seb\Infra\Repo\Ticket\Interfaces;

use DateTimeInterface as DateTime;

interface CreateTicketRepository
{
    public function createTicket(DateTime $emitionMoment, int $code, string $status): array;
}
