<?php

namespace Seb\Infra\Repo\Balcony\MySQL;

use DateTimeInterface;
use Exception;
use PDO;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusValueObject as BalconyStatus;
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

    public function updateBalconyStatus(int $balconyNumber, BalconyStatus $balconyStatus): bool
    {
        $query = '
            update balconies set status = :status where number = :number;
        ';

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':status', $balconyStatus);
        $statment->bindParam(':number', $balconyNumber);

        return $statment->execute();
    }

    public function setEndServiceMoment(int $ticketId,int $balconyNumber, DateTimeInterface $endMoment): bool
    {
        $query = '
            update services set
                end_moment = :end_moment
            where
                ticket_id = :ticket_id and
                balcony_number = :balcony_number;
        ';

        $endMoment = $endMoment->format('Y-m-d H:i:s');
        
        $statment = $this->connection->prepare($query);
        $statment->bindParam(':end_moment', $endMoment);
        $statment->bindValue(':ticket_id', $ticketId);
        $statment->bindParam(':balcony_number', $balconyNumber);

        return $statment->execute();
    }

    public function readBalconyStatus(int $balconyNumber): BalconyStatus
    {
        $query = '
            select status from balconies where number = :number;
        ';

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':number', $balconyNumber);

        $statment->execute();
        $balcony = $statment->fetch(PDO::FETCH_ASSOC);
        if (is_bool($balcony)) throw new Exception("balcony " . $balconyNumber . ' not found!', 1);

        return new BalconyStatus($balcony['status']);
    }
}
