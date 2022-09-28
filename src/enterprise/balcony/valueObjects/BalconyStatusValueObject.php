<?php

namespace Seb\Enterprise\Balcony\ValueObjects;

use DomainException;

final class BalconyStatusValueObject
{
    public function __construct(
        private string $status
    ){
        if (
            $status !== 'not in service' &&
            $status !== 'in service' &&
            $status !== 'inactive'
        ) {
            throw new DomainException("status must be only: in service, not in service, inactive", 1);
        }

        $this->status = $status;
    }

    public function __toString() { return $this->status; }
}
