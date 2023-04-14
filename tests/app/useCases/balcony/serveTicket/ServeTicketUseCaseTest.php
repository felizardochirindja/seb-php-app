<?php

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Seb\Adapters\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Adapters\Repo\Service\Interfaces\ServiceRepository;
use Seb\Adapters\Repo\Ticket\Interfaces\TicketRepository;
use Seb\App\UseCases\Balcony\ServeTicket\ServeTicketUseCase;

final class ServeTicketUseCaseTest extends TestCase
{
    public function testExecuteWithNotFoundBalcony(): void
    {
        $balconyRepositoryMock = $this->createStub(BalconyRepository::class);
        $balconyRepositoryMock->method('readBalconyStatus')->willReturn(false);

        $ticketRepositoryMock = $this->createStub(TicketRepository::class);
        $serviceRepositoryMock = $this->createStub(ServiceRepository::class);
        $loggerMock = $this->createStub(LoggerInterface::class);

        $serveTicketUseCase = new ServeTicketUseCase(
            $ticketRepositoryMock,
            $balconyRepositoryMock,
            $serviceRepositoryMock,
            $loggerMock
        );

        $balconyNumber = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("balcony $balconyNumber not found!");

        $serveTicketUseCase->execute(1);
    }
}
