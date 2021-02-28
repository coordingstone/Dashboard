<?php
namespace Dashboard\Database;

use mysqli_result;
use PDOException;
use Exception;
use PDO;
use PDOStatement;

class DbStatementHelper
{
    /**
     * @var Db
     */
    private Db $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $query
     * @return bool|false|mysqli_result|PDOStatement
     * @throws Exception
     */
    public function selectAll(string $query)
    {
        try {
            return $statement = $this->db->getConnection()->query($query);
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * @param string $query
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function selectAllByParams(string $query, array $params)
    {
        try {
            $statement = $this->db->getConnection()->prepare($query);
            $statement->execute($params);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * @param string $query
     * @param array $params
     * @param bool $returnObjectId
     * @return bool|string
     * @throws Exception
     */
    public function insert(string $query, array $params, bool $returnObjectId = false)
    {
        try {
            if ($returnObjectId) {
                $statement = $this->db->getConnection()->prepare($query);
                $statement->execute($params);
                return $this->db->getConnection()->lastInsertId();
            } else {
                return $statement = $this->db->getConnection()->prepare($query)->execute($params);
            }
            
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * @param string $query
     * @param string $value
     * @return bool|mixed
     * @throws Exception
     */
    public function selectByValue(string $query, string $value)
    {
        try {
            $statement = $this->db->getConnection()->prepare($query);
            $statement->bindParam(':value', $value, PDO::PARAM_STR);
            $statement->execute();
            return $statement->fetch();
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }

    /**
     * @param string $query
     * @param int $id
     * @return bool|mixed
     * @throws Exception
     */
    public function selectById(string $query, int $id)
    {
        try {
            $statement = $this->db->getConnection()->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch();
        } catch (PDOException $e) {
            throw new Exception('Query failed: ' . $e->getMessage());
        }
    }
}
