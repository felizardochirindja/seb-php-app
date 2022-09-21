<?php

namespace Seb\Platform\Presenters\Interfaces;

interface TicketPresenterInterface
{
    public function outputPDF(array $ticketData): string;
}
