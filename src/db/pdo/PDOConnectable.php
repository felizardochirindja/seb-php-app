<?php

namespace Seb\DB\PDO;

use PDO;

interface PDOConnectable
{
    public function getConnection(): PDO;
}
