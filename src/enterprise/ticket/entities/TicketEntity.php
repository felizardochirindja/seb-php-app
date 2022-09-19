<?php

namespace Seb\Enterprise\Ticket\Entities;

use DateTime;
use DomainException;

final class TicketEntity
{
    private DateTime $emitionMoment;
    private int $code;
    private string $status;

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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        if (
            $status !== 'pending' &&
            $status !== 'in service' &&
            $status !== 'attended'
        ) {
            throw new DomainException("status must be only: pending, in service, attended", 1);
        }

        $this->status = $status;
        return $this;
    }
}
