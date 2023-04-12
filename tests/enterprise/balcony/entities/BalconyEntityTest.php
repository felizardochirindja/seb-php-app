<?php

use PHPUnit\Framework\TestCase;
use Seb\Enterprise\Balcony\Entities\BalconyEntity;

final class BalconyEntityTest extends TestCase
{
    public function testValidNumber() {
        $ticketEntity = new BalconyEntity();
        $result = $ticketEntity->setNumber(1);
        $this->assertInstanceOf(BalconyEntity::class, $result);
    }

    public function testInvalidNumber() {
        $ticketEntity = new BalconyEntity();
        $this->expectException(DomainException::class);
        $ticketEntity->setNumber(0);
    }
}