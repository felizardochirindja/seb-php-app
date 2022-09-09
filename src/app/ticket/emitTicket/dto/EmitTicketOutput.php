<?php

namespace Seb\App\Ticket\EmitTicket\DTO;

final class EmitTicketOutput
{
    public function __construct(
        public string $emitionMoment,
        public int $code,
        public string $companyName,
        public string $messageForClient,
    ) {}
}
