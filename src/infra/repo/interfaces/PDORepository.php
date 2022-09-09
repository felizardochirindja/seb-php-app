<?php

namespace Seb\Infra\Repo\Interfaces;

use PDO;

abstract class PDORepository
{
    public function __construct(protected PDO $connection){}
}
