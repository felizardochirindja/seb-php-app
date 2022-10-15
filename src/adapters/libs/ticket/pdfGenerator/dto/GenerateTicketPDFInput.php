<?php

namespace Seb\Adapters\Libs\Ticket\PDFGenerator\DTO;

final class GenerateTicketPDFInput
{
    public function __construct(
        public int $ticketCode,
        public string $date,
        public string $time,
        public string $message,
        public string $ticketsFollowing,
    ) {}
}
