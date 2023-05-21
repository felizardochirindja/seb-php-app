<?php

namespace Seb\Adapters\Repo\Balcony\MySQL;

use Exception;
use PDO;
use Seb\Adapters\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Adapters\Repo\Interfaces\PDORepository;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusEnum as BalconyStatus;

final class PDOBalconyRepository extends PDORepository implements BalconyRepository
{
    public function updateBalconyStatus(int $balconyNumber, BalconyStatus $balconyStatus): bool
    {
        $query = '
            update balconies set status = :status where number = :number;
        ';

        $balconyStatus = $balconyStatus->value;

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':status', $balconyStatus);
        $statment->bindParam(':number', $balconyNumber);

        return $statment->execute();
    }

    public function readBalconyStatus(int $balconyNumber): BalconyStatus | false
    {
        $query = '
            select status from balconies where number = :number;
        ';

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':number', $balconyNumber);

        $statment->execute();
        $balcony = $statment->fetch(PDO::FETCH_ASSOC);

        if (is_bool($balcony)) return false;

        return BalconyStatus::from($balcony['status']);
    }

    public function verifyActiveBalconies(): bool
    {
        $query = '
            select * from balconies where status = :first_status or status = :second_status;
        ';

        $statment = $this->connection->prepare($query);
        $statment->bindValue(':first_status', 'not in service');
        $statment->bindValue(':second_status', 'in service');
        
        if (!$statment->execute()) {
            throw new Exception("error while executing query");
        }
        
        $activeBalconiesNumber = $statment->rowCount();
        return ($activeBalconiesNumber < 1) ? false : true;
    }
}
