<?php

class Database {
    private $conn;
    public function __construct(){
        $config_db = [
            'host' => 'localhost',
            'database_name' => 'todolist',
            'user' => 'root',
            'password' => ''
        ];
        $dsn = 'mysql:host='.$config_db['host'] . '; dbname=' . $config_db['database_name'];
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        try{
            $this->conn = new PDO($dsn, $config_db['user'], $config_db['password'], $options);
        } catch (PDOException $error){
            echo $error->getMessage();
        }
    }

    public function query($sql){
        return $this->conn->query($sql);
    }
}