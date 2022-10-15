<?php

namespace Seb\Adapters\Libs\Ticket\PDFGenerator\DomPDF;

use Dompdf\Dompdf;
use Seb\Adapters\Libs\Ticket\PDFGenerator\DTO\GenerateTicketPDFInput;
use Seb\Adapters\Libs\Ticket\PDFGenerator\TicketPDFGeneratable as TicketPDFGenerator;

class DomPDFTicketGeneratorAdapter implements TicketPDFGenerator
{
    public function __construct(
        private Dompdf $dompdf,
    ){}

    public function generate(GenerateTicketPDFInput $ticketData): string
    {
        $this->dompdf->loadHtml('hello world');
        $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->render();

        return $this->dompdf->output();
    }
}
