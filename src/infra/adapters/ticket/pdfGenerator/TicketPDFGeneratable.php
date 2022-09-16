<?php

namespace Seb\Infra\Adapters\Ticket\PDFGenerator;

interface TicketPDFGeneratable
{
    public function generate(array $ticketData): string;
}
