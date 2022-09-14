<?php

namespace Seb\DB\PDO;

use PDO;

abstract class PDOConnector implements PDOConnectable
{
    protected static ?PDO $connection = null;
    
    public function __construct(
        protected string $dataSourceName,
        protected string $username = 'root',
        protected string $password = '',
    ) {}

    protected function __clone() {}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
