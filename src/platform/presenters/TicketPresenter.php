<?php

namespace Seb\Platform\Presenters;

use Seb\Platform\Presenters\Interfaces\TicketPresenterInterface;

final class TicketPresenter implements TicketPresenterInterface
{
    public function outputPDF(array $ticketData): string
    {
        return $ticketData['pdf_code'];
    }
}
