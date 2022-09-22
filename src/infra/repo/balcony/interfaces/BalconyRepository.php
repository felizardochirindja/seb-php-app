<?php

namespace Seb\Infra\Repo\Balcony\Interfaces;

use DateTimeInterface;

interface BalconyRepository
{
    public function createBalconyService(int $ticketId, int $balconyId, DateTimeInterface $startMoment): bool;
    public function updateBalconyStatus(int $balconyId, string $balconyStatus): bool;
}
