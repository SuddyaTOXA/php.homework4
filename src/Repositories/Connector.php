<?php

namespace Repositories;

class Connector
{
    private $pdo;
    /**
     * StudentsRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param $server_name
     * @param $database_name
     * @param $user
     * @param $pass
     */
    public function __construct($server_name, $database_name, $user, $pass)
    {
        try {
            $this->pdo = new \PDO('mysql:host=localhost;dbname=' . $database_name . ';charset=UTF8', $user, $pass);
        }
        catch(\PDOException $e)
        {
            $this->pdo = new \PDO('mysql:host='.$server_name.'', $user, $pass);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $sql = 'CREATE DATABASE IF NOT EXISTS '. $database_name;
            $this->pdo->exec($sql);
            $sql = 'use '. $database_name;
            $this->pdo->exec($sql);
            $sql = 'CREATE TABLE IF NOT EXISTS universities (
                        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        university_name CHAR (50),
                        city CHAR (50),
                        site_ulr CHAR (100)
                    )ENGINE=INNODB;
                    CREATE TABLE IF NOT EXISTS departments (
                        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        department_name CHAR (50),
                        university_id INT ,
                        FOREIGN KEY (university_id) REFERENCES universities(id)
                    )ENGINE=INNODB;
                    CREATE TABLE IF NOT EXISTS students (
                        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        first_name CHAR (30), 
                        last_name CHAR (30), 
                        email CHAR(60), 
                        tell CHAR(12),
                        department_id INT,
                        FOREIGN KEY (department_id) REFERENCES departments(id)
                    )ENGINE=INNODB;
                    CREATE TABLE IF NOT EXISTS teachers (
                        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        first_name CHAR (30), 
                        last_name CHAR (30), 
                        department_id INT,
                        FOREIGN KEY (department_id) REFERENCES departments(id)
                    )ENGINE=INNODB;
                    CREATE TABLE IF NOT EXISTS disciplines (
                        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        discipline_name CHAR (30), 
                        department_id INT,
                        FOREIGN KEY (department_id) REFERENCES departments(id)
                    )ENGINE=INNODB;
                    CREATE TABLE IF NOT EXISTS homeworks (
                        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        homework_name CHAR (30),
                        description TEXT,
                        department_id INT,
                        FOREIGN KEY (department_id) REFERENCES departments(id)
                    )ENGINE=INNODB;
                    ';
            $this->pdo->exec($sql);
        }
    }


    public function getPdo()
    {
        return $this->pdo;
    }
}