<?php

namespace Seb\Adapters\Repo\Service\MySQL;

use DateTimeInterface;
use Seb\Adapters\Repo\Interfaces\PDORepository;
use Seb\Adapters\Repo\Service\Interfaces\ServiceRepository;
use PDO;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusEnum as TicketStatus;

final class PDOServiceRepository extends PDORepository implements ServiceRepository
{
    public function readServiceByBalconyNumberAndStatus(int $balconyNumber, TicketStatus $ticketStatus): array
    {
        $query = '
            select * from tickets t
                join services s
                on t.id = s.ticket_id
            where s.balcony_number = :balcony_number and t.status = :ticket_status;
        ';

        $ticketStatus = $ticketStatus->value;

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':ticket_status', $ticketStatus);
        $statment->bindParam(':balcony_number', $balconyNumber);
        $statment->execute();

        $service = $statment->fetch(PDO::FETCH_ASSOC);

        if ($statment->rowCount() === 0) return [];
        return $service;
    }

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
}
