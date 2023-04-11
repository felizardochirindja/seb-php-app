<?php

namespace Seb\Enterprise\Balcony\Entities;

use DomainException;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusEnum as BalconyStatus;

final class BalconyEntity
{
    private string $attendantName;
    private int $number;
    private BalconyStatus $status;
 
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
        if ($number < 1)
            throw new DomainException("balcony number must be greeter than 0", 1);

        $this->number = $number;
        return $this;
    }

    public function getStatus(): BalconyStatus
    {
        return $this->status;
    }

    public function setStatus(BalconyStatus $status): self
    {
        $this->status = $status;
        return $this;
    }
}
