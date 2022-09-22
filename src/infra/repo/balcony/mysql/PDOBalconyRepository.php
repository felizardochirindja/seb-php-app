<?php

namespace Seb\Infra\Repo\Balcony\MySQL;

use DateTimeInterface;
use Seb\Infra\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Infra\Repo\Interfaces\PDORepository;

final class PDOBalconyRepository extends PDORepository implements BalconyRepository
{
    public function createBalconyService(int $ticketId, int $balconyNumber, DateTimeInterface $startMoment): bool
    {
        $query = '
            insert into services set
                ticket_id = :ticket_id,
                start_moment = :start_moment,
                balcony_number = :balcony_number;
        ';

        $startMoment = $startMoment->format('Y-m-d H:i:s');

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':ticket_id', $ticketId);
        $statment->bindParam(':start_moment', $startMoment);
        $statment->bindParam(':balcony_number', $balconyNumber);

        return $statment->execute();
    }

    public function updateBalconyStatus(int $balconyNumber, string $balconyStatus): bool
    {
        return false;
    }
}
