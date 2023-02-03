<?php

namespace Seb\Adapters\Repo\Balcony\Interfaces;

use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusEnum as BalconyStatus;

interface BalconyRepository
{
    public function updateBalconyStatus(int $balconyNumber, BalconyStatus $balconyStatus): bool;
    public function readBalconyStatus(int $balconyNumber): BalconyStatus;
    public function verifyActiveBalconies(): bool;
}
