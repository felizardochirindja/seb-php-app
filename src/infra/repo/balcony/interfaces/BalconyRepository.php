<?php

namespace Seb\Infra\Repo\Balcony\Interfaces;

use DateTimeInterface;

interface BalconyRepository
{
    public function createBalconyService(int $ticketId, int $balconyNumber, DateTimeInterface $startMoment): bool;
    public function updateBalconyStatus(int $balconyNumber, string $balconyStatus): bool;
    public function setEndServiceMoment(int $ticketId,int $balconyNumber, DateTimeInterface $endMoment): bool;
}
