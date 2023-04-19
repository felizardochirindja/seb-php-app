<?php

namespace Seb\Adapters\Repo\Ticket\MySQL;

use DateTimeInterface as DateTime;
use Exception;
use PDO;
use Seb\Adapters\Repo\Interfaces\PDORepository;
use Seb\Adapters\Repo\Ticket\Interfaces\TicketRepository;
use Seb\Enterprise\Ticket\ValueObjects\TicketStatusEnum as TicketStatus;

final class PDOTicketRepository extends PDORepository implements TicketRepository
{
    public function createTicket(DateTime $emitionMoment, int $code, TicketStatus $status): array
    {
        $query = '
            insert into tickets set
            code = :code,
            emition_moment = :emition_moment,
            status = :status;
        ';

        $formatetEmitionMoment = $emitionMoment->format('Y-m-d H:i:s');
        $status = $status->value;

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':code', $code);
        $statment->bindParam(':emition_moment', $formatetEmitionMoment);
        $statment->bindParam(':status', $status);
        $statment->execute();

        $insertedTicketId = $this->connection->lastInsertId();
        if ($insertedTicketId === false) throw new Exception("error while looking the last inserted id");

        $ticket = $this->readTicketById($insertedTicketId);
        return $ticket;
    }

    private function readTicketById(int $id): array
    {
        $query = '
            select * from tickets where id = :id;
        ';

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':id', $id);
        $statment->execute();

        $ticket = $statment->fetch(PDO::FETCH_ASSOC);

        if ($statment->rowCount() === 0) throw new Exception("record with #id of $id not found!");;

        return $ticket;
    }

    public function readLastInsertedTicket(): array
    {
        $query = '
            select * from tickets order by id desc limit 1;
        ';
        $statment = $this->connection->prepare($query);
        $statment->execute();

        $ticket = $statment->fetch(PDO::FETCH_ASSOC);
        return ($statment->rowCount() === 0) ? [] : $ticket;
    }

    public function readFirstFoundTicketByStatus(TicketStatus $ticketStatus): array
    {
        $query = '
            select * from tickets where status = :status limit 1;
        ';

        $ticketStatus = $ticketStatus->value;

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':status', $ticketStatus);
        $statment->execute();

        $ticket = $statment->fetch(PDO::FETCH_ASSOC);
        return ($statment->rowCount() === 0) ? [] : $ticket;
    }

    public function updateTicketStatus(int $ticketId, TicketStatus $ticketStatus): bool
    {
        $query = '
            update tickets set status = :status where id = :id;
        ';

        $ticketStatus = $ticketStatus->value;

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':status', $ticketStatus);
        $statment->bindParam(':id', $ticketId);

        return $statment->execute();
    }

    public function readTicketsByStatus(TicketStatus $ticketStatus): array
    {
        $query = '
            select * from tickets where status = :status;
        ';

        $ticketStatus = $ticketStatus->value;

        $statment = $this->connection->prepare($query);
        $statment->bindParam(':status', $ticketStatus);
        $statment->execute();

        $tickets = $statment->fetch(PDO::FETCH_ASSOC);
        return ($statment->rowCount() === 0) ? [] : $tickets;
    }
}
