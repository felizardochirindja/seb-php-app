<?php

namespace Seb\Enterprise\Ticket\ValueObjects;

enum TicketStatusEnum: string
{
    case Pending = 'pending';
    case InService = 'in service';
    case Attended = 'attended';
} 
