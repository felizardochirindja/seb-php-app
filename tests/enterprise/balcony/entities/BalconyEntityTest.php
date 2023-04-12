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
        $balconyEntity = new BalconyEntity();
        $result = $balconyEntity->setNumber(1);
        $this->assertInstanceOf(BalconyEntity::class, $result);
    }

    public function testInvalidNumber() {
        $balconyEntity = new BalconyEntity();
        $this->expectException(DomainException::class);
        $balconyEntity->setNumber(0);
    }
}