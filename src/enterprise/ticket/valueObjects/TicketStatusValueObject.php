<?php

namespace Seb\Enterprise\Ticket\ValueObjects;

use DomainException;

final class TicketStatusValueObject
{
    public function __construct(
        private string $status
    ){
        if (
            $status !== 'pending' &&
            $status !== 'in service' &&
            $status !== 'attended'
        ) {
            throw new DomainException("status must be only: pending, in service, attended", 1);
        }

        $this->status = $status;
    }

    public function __toString() { return $this->status; }
}
