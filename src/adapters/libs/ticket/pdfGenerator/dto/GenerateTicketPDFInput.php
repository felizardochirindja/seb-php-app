<?php

namespace Seb\Adapters\Libs\Ticket\PDFGenerator\DTO;

final class GenerateTicketPDFInput
{
    public function __construct(
        public readonly int $ticketCode,
        public readonly string $date,
        public readonly string $time,
        public readonly string $message,
        public readonly string $ticketsFollowing,
    ) {}
}
