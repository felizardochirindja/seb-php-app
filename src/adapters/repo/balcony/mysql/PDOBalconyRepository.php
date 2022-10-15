<?php

namespace Seb\Adapters\Repo\Balcony\MySQL;

use DateTimeInterface;
use Exception;
use PDO;
use Seb\Adapters\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Adapters\Repo\Interfaces\PDORepository;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusValueObject as BalconyStatus;

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

    public function verifyActiveBalconies(): bool
    {
        $query = '
            select * from balconies where status = :first_status or status = :second_status;
        ';

        $statment = $this->connection->prepare($query);
        $statment->bindValue(':first_status', 'not in service');
        $statment->bindValue(':second_status', 'in service');

        $statment->execute();
        $activeBalconiesNumber = $statment->rowCount();
        return ($activeBalconiesNumber < 1) ? false : true;
    }
}
