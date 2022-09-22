<?php

namespace Seb\App\Ticket\EmitTicket\DTO;

final class EmitTicketOutput
{
    public function __construct(
        public int $ticketCode,
        public string $pdfCode,
        public string $date,
        public string $time,
    ) {}
}
