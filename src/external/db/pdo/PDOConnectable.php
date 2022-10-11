<?php

namespace Seb\External\DB\PDO;

use PDO;

interface PDOConnectable
{
    public function getConnection(): PDO;
}
