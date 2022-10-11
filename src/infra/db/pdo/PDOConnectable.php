<?php

namespace Seb\Infra\DB\PDO;

use PDO;

interface PDOConnectable
{
    public function getConnection(): PDO;
}
