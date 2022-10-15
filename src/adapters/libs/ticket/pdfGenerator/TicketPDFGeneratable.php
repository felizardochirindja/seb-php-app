<?php

namespace Seb\Adapters\Libs\Ticket\PDFGenerator;

use Seb\Adapters\Libs\Ticket\PDFGenerator\DTO\GenerateTicketPDFInput;

interface TicketPDFGeneratable
{
    public function generate(GenerateTicketPDFInput $ticketData): string;
}
