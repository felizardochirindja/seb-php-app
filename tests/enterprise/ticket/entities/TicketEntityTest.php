<?php

use PHPUnit\Framework\TestCase;
use Seb\Enterprise\Ticket\Entities\TicketEntity;

final class TicketEntityTest extends TestCase
{
    public function testValidCode() {
        $ticketEntity = new TicketEntity();
        $result = $ticketEntity->setCode(100);
        $this->assertInstanceOf(TicketEntity::class, $result);
    }

    public function testInvalidCode() {
        $ticketEntity = new TicketEntity();
        $this->expectException(DomainException::class);
        $ticketEntity->setCode(99);
    }
}
