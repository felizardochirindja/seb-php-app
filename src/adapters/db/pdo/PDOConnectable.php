<?php

namespace Seb\Adapters\DB\PDO;

use PDO;

interface PDOConnectable
{
    public function getConnection(): PDO;
}
