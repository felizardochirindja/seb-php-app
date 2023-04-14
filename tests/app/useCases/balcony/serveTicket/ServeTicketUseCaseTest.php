<?php

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Seb\Adapters\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Adapters\Repo\Service\Interfaces\ServiceRepository;
use Seb\Adapters\Repo\Ticket\Interfaces\TicketRepository;
use Seb\App\UseCases\Balcony\ServeTicket\ServeTicketUseCase;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusEnum;

final class ServeTicketUseCaseTest extends TestCase
{
    private BalconyRepository $balconyRepositoryMock;
    private TicketRepository $ticketRepositoryMock;
    private ServiceRepository $serviceRepositoryMock;
    private LoggerInterface $loggerMock;
    private ServeTicketUseCase $serveTicketUseCase;

    public function setUp(): void
    {
        $this->balconyRepositoryMock = $this->createStub(BalconyRepository::class);
        $this->ticketRepositoryMock = $this->createStub(TicketRepository::class);
        $this->serviceRepositoryMock = $this->createStub(ServiceRepository::class);
        $this->loggerMock = $this->createStub(LoggerInterface::class);
        
        $this->serveTicketUseCase = new ServeTicketUseCase(
            $this->ticketRepositoryMock,
            $this->balconyRepositoryMock,
            $this->serviceRepositoryMock,
            $this->loggerMock
        );
    }

    public function testExecuteWithNotFoundBalcony(): void
    {
        $this->balconyRepositoryMock->method('readBalconyStatus')->willReturn(false);

        $balconyNumber = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("balcony $balconyNumber not found!");

        $this->serveTicketUseCase->execute(1);
    }

    public function testExecuteWithBalconyInService(): void
    {
        $this->balconyRepositoryMock->method('readBalconyStatus')->willReturn(BalconyStatusEnum::InService);

        $balconyNumber = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("balcony $balconyNumber is already in service");

        $this->serveTicketUseCase->execute($balconyNumber);
    }
}