<?php

namespace Seb\Enterprise\Balcony\ValueObjects;

enum BalconyStatusEnum: string
{
    case Inactive = 'inactive';
    case InService = 'in service';
    case NotInService = 'not in service';
} 
