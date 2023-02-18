<?php

namespace Seb\Enterprise\Service;

use Seb\Enterprise\Balcony\Entities\BalconyEntity;
use Seb\Enterprise\Ticket\Entities\TicketEntity;
use DateTimeInterface;

final class ServiceEntity
{
    private TicketEntity $ticket;
    private BalconyEntity $balcony;
    private DateTimeInterface $startMoment;
    private DateTimeInterface $endMoment;

    public function getTicket(): TicketEntity
    {
        return $this->ticket;
    }

    public function setTicket(TicketEntity $ticket): self
    {
        $this->ticket = $ticket;
        return $this;
    }

    public function getBalcony(): BalconyEntity
    {
        return $this->balcony;
    }

    public function setBalcony(BalconyEntity $balcony): self
    {
        $this->balcony = $balcony;
        return $this;
    }

    public function getStartMoment(): DateTimeInterface
    {
        return $this->startMoment;
    }

    public function setStartMoment($startMoment): self
    {
        $this->startMoment = $startMoment;
        return $this;
    }

    public function getEndMoment(): DateTimeInterface
    {
        return $this->endMoment;
    }

    public function setEndMoment(DateTimeInterface $endMoment): self
    {
        $this->endMoment = $endMoment;
        return $this;
    }
}
