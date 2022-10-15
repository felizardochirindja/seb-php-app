<?php

namespace Seb\Adapters\Repo\Interfaces;

use PDO;

abstract class PDORepository
{
    public function __construct(protected PDO $connection){}
}
