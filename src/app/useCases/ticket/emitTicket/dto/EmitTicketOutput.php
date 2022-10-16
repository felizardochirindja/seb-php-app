<?php

namespace Seb\App\UseCases\Ticket\EmitTicket\DTO;

final class EmitTicketOutput
{
    public function __construct(
        public readonly int $ticketCode,
        public readonly string $pdfCode,
        public readonly string $date,
        public readonly string $time,
    ) {}
}
