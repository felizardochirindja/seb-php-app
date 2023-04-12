<?php

use PHPUnit\Framework\TestCase;
use Seb\Enterprise\Balcony\Entities\BalconyEntity;

final class BalconyEntityTest extends TestCase
{
    public function testGetName()
    {
        $balconyEntity = new BalconyEntity();
        $balconyEntity->setNumber(2);
        $this->assertEquals('balcão ' . 2, $balconyEntity->getName());
    }

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