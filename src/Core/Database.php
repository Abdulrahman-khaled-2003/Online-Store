<?php

namespace App\Core;

use App\Core\Exception\QueryException;
use PDO;
use PDOException;

require "Exceptions/QueryException.php";

class Database
{
    private $connection;
    private $statement;

    public function __construct(array $config = [])
    {
        $dsn = "mysql:" . http_build_query($config, "", ";");
        $this->connection = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    private function query(string $sql, array $params = [])
    {
        try {
            $this->statement = $this->connection->prepare($sql);
            $this->statement->execute($params);
        } catch (PDOException $e) {
            
            throw new QueryException($e->getMessage());
        }
    }

    public function fetchAll(string $sql, array $params = [])
    {
        $this->query($sql, $params);
        return $this->statement->fetchAll();
    }

    public function fetch(string $sql, array $params = [])
    {
        $this->query($sql, $params);
        return $this->statement->fetch();
    }

    public function execute(string $sql, array $params = [])
    {
        $this->query($sql, $params);
    }

    public function disConnect()
    {
        $this->connection = null;
    }

    public function getLastId(){
       return $this->connection->lastInsertId();
    }
}
