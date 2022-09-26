<?php

namespace Seb\Enterprise\Ticket\Entities;

use DateTime;
use DomainException;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusValueObject as TicketStatus;

final class TicketEntity
{
    private DateTime $emitionMoment;
    private int $code;
    private TicketStatus $status;

    public function getEmitionMoment(): DateTime
    {
        return $this->emitionMoment;
    }

    public function setEmitionMoment(DateTime $emitionMoment): self
    {
        $this->emitionMoment = $emitionMoment;
        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        if ($code < 100) {
            throw new DomainException("code must be greeter than 99", 1);
        }

        $this->code = $code;
        return $this;
    }

    public function getStatus(): TicketStatus
    {
        return $this->status;
    }

    public function setStatus(TicketStatus $status): self
    {
        $this->status = $status;
        return $this;
    }
}
