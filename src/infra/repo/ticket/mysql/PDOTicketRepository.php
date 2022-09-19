<?php

namespace Seb\Infra\Repo\Ticket\MySQL;

use DateTimeInterface as DateTime;
use PDO;
use Seb\Infra\Repo\Interfaces\PDORepository;
use Seb\Infra\Repo\Ticket\Interfaces\TicketRepository;

final class PDOTicketRepository extends PDORepository implements TicketRepository
{
    public function createTicket(DateTime $emitionMoment, int $code, string $status): array
    {
        $query = '
            insert into tickets set
            code = :code,
            emition_moment = :emition_moment,
            status = :status;
        ';

        $formatetEmitionMoment = $emitionMoment->format('Y-m-d H:i:s');

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':code', $code);
        $statment->bindParam(':emition_moment', $formatetEmitionMoment);
        $statment->bindParam(':status', $status);
        $statment->execute();

        $insertedTicketId = $this->getLastTicketInsertdId();
        $ticket = $this->readTicketById($insertedTicketId);
        return $ticket;
    }

    private function getLastTicketInsertdId(): int
    {
        $ticketId = $this->connection->lastInsertId();
        return $ticketId;
    }

    public function readTicketById(int $id): array
    {
        $query = '
            select * from tickets where id = :id;
        ';

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':id', $id);
        $statment->execute();

        $ticket = $statment->fetch(PDO::FETCH_ASSOC);
        return $ticket;
    }
}
