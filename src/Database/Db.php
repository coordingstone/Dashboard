<?php
namespace Dashboard\Database;

use Exception;
use mysqli;
use PDO;

class Db
{
    /** @var mysqli|PDO */
    private $connection;

    /**
     * @param PDO $pdo
     * @throws Exception
     */
    public function __construct(PDO $pdo)
    {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $this->connection = $pdo;
    }

    /**
     * @return mysqli|PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }


}