<?php

namespace Seb\External\DB\PDO\MySQL;

use PDO;
use PDOException;
use Seb\External\DB\PDO\PDOConnector;

final class MySQLPDOConnector extends PDOConnector
{
    public function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO($this->dataSourceName, $this->username, $this->password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'Connection Error: ' . $e->getMessage();
            }
        }
        return self::$connection;
    }
}
