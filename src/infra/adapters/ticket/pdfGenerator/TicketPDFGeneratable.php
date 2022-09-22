<?php

namespace Seb\Infra\Adapters\Ticket\PDFGenerator;

use Seb\Infra\Adapters\Ticket\PDFGenerator\DTO\GenerateTicketPDFInput;

interface TicketPDFGeneratable
{
    public function generate(GenerateTicketPDFInput $ticketData): string;
}
