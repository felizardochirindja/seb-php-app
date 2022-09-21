<?php

namespace Seb\Infra\Adapters\Ticket\PDFGenerator\DomPDF;

use Dompdf\Dompdf;
use Seb\Infra\Adapters\Ticket\PDFGenerator\TicketPDFGeneratable as TicketPDFGenerator;

class DomPDFTicketGeneratorAdapter implements TicketPDFGenerator
{
    public function __construct(
        private Dompdf $dompdf,
    ){}

    public function generate(array $ticketData): string
    {
        $this->dompdf->loadHtml('hello world');
        $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->render();

        return $this->dompdf->output();
    }
}
