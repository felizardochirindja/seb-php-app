<?php

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Seb\Adapters\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Adapters\Repo\Service\Interfaces\ServiceRepository;
use Seb\Adapters\Repo\Ticket\Interfaces\TicketRepository;
use Seb\App\UseCases\Balcony\MarkTicketAsServed\MarkTicketAsServedUseCase;
use Seb\Enterprise\Balcony\ValueObjects\BalconyStatusEnum;

final class MarkTicketAsServedUseCaseTest extends TestCase
{
    private BalconyRepository | MockObject $balconyRepositoryStub;
    private TicketRepository | MockObject $ticketRepositoryStub;
    private ServiceRepository | MockObject $serviceRepositoryStub;
    private LoggerInterface | MockObject $loggerStub;
    private MarkTicketAsServedUseCase $markTicketAsServedUseCase;

    public function setUp(): void
    {
        $this->balconyRepositoryStub = $this->createStub(BalconyRepository::class);
        $this->ticketRepositoryStub = $this->createStub(TicketRepository::class);
        $this->serviceRepositoryStub = $this->createStub(ServiceRepository::class);
        $this->loggerStub = $this->createStub(LoggerInterface::class);
        
        $this->markTicketAsServedUseCase = new MarkTicketAsServedUseCase(
            $this->ticketRepositoryStub,
            $this->balconyRepositoryStub,
            $this->serviceRepositoryStub,
            $this->loggerStub
        );
    }

    public function testWithBalconyNotServingAnyTicket(): void
    {
        $this->balconyRepositoryStub
            ->method('readBalconyStatus')
            ->willReturn(BalconyStatusEnum::NotInService);

        $balconyNumber = 1;
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("balcony $balconyNumber is not serving any ticket");
        
        $this->markTicketAsServedUseCase->execute($balconyNumber);
    }
}
