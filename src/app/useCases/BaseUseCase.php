<?php

namespace Seb\App\UseCases;

use Psr\Log\LoggerInterface;

abstract class BaseUseCase
{
    public function __construct(protected LoggerInterface $logger) {}
}
