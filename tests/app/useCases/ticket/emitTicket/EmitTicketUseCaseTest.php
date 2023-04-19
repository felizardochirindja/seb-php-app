<?php

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Seb\Adapters\Libs\Ticket\PDFGenerator\TicketPDFGeneratable;
use Seb\Adapters\Repo\Balcony\Interfaces\BalconyRepository;
use Seb\Adapters\Repo\Ticket\Interfaces\TicketRepository;
use Seb\App\UseCases\Ticket\EmitTicket\DTO\EmitTicketOutput;
use Seb\App\UseCases\Ticket\EmitTicket\EmitTicketUseCase;

final class EmitTicketUseCaseTest extends TestCase
{
    private TicketRepository | MockObject $ticketRepositoryStub;
    private BalconyRepository | MockObject $balconyRepositoryStub;
    private TicketPDFGeneratable | MockObject $pdfGeneratorStub;
    private LoggerInterface | MockObject $loggerStub;
    private EmitTicketUseCase $emitTicketUseCase;

    public function setUp(): void
    {
        $this->ticketRepositoryStub = $this->createStub(TicketRepository::class);
        $this->balconyRepositoryStub = $this->createStub(BalconyRepository::class);
        $this->pdfGeneratorStub = $this->createStub(TicketPDFGeneratable::class);
        $this->loggerStub = $this->createStub(LoggerInterface::class);
        
        $this->emitTicketUseCase = new EmitTicketUseCase(
            $this->ticketRepositoryStub,
            $this->balconyRepositoryStub,
            $this->pdfGeneratorStub,
            $this->loggerStub
        );
    }

    public function testWithoutAnyActiveBalcony(): void
    {
        $this->balconyRepositoryStub
            ->method('verifyActiveBalconies')
            ->willReturn(false);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('there is no any active balcony');

        $this->emitTicketUseCase->execute();
    }

    public function testEmitTicketCorrectly(): void
    {
        $this->balconyRepositoryStub
            ->method('verifyActiveBalconies')
            ->willReturn(true);

        $this->ticketRepositoryStub
            ->method('readTicketsByStatus')
            ->willReturn([]);

        $this->ticketRepositoryStub
            ->method('readLastInsertedTicket')
            ->willReturn(['code' => 100]);

        $this->ticketRepositoryStub
            ->method('createTicket')
            ->willReturn([
                'emition_moment' => '2023-02-03 02:37:04',
                'code' => 100
            ]);

        $this->pdfGeneratorStub
            ->method('generate')
            ->willReturn('pdf code');

        $this->loggerStub
            ->method('notice')
            ->willReturnCallback(function(): void {});

        $this->assertEquals(
            new EmitTicketOutput(
                100,
                'pdf code',
                '03/02/2023',
                '02:37:04'
            ),
            $this->emitTicketUseCase->execute()
        );
    }
}