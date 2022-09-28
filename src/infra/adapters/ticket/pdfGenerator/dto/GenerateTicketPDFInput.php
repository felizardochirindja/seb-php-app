<?php

namespace Seb\Infra\Adapters\Ticket\PDFGenerator\DTO;

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
