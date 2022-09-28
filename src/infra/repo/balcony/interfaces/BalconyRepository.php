<?php

namespace Seb\Infra\Repo\Balcony\Interfaces;

use DateTimeInterface;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusValueObject as BalconyStatus;

interface BalconyRepository
{
    public function createBalconyService(int $ticketId, int $balconyNumber, DateTimeInterface $startMoment): bool;
    public function updateBalconyStatus(int $balconyNumber, BalconyStatus $balconyStatus): bool;
    public function setEndServiceMoment(int $ticketId,int $balconyNumber, DateTimeInterface $endMoment): bool;
    public function readBalconyStatus(int $balconyNumber): BalconyStatus;
}
