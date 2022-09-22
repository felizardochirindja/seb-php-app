<?php

namespace Seb\Enterprise\Balcony\Entities;

use DomainException;

final class BalconyEntity
{
    private string $attendantName;
    private int $number;
    private string $status;
 
    public function getName(): string
    {
        return "balcão " . $this->number;
    }
 
    public function getAttendantName(): string
    {
        return $this->attendantName;
    }
 
    public function setAttendantName($attendantName): self
    {
        $this->attendantName = $attendantName;
        return $this;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber($number): self
    {
        if ($number < 1) {
            throw new DomainException("balcony number must be greeter than 0", 1);
        }

        $this->number = $number;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        if (
            $status !== 'not in service' &&
            $status !== 'in service'
        ) {
            throw new DomainException("status must be only: in service, not in service", 1);
        }

        $this->status = $status;
        return $this;
    }
}
