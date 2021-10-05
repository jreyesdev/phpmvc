<?php

class Initial{
    public $pdo;
    public function __construct(){
        $this->pdo = \App\Core\App::$app->db->pdo;
    }
    public function up(){
        $SQL = "CREATE TABLE users(
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        ) ENGINE=INNODB;";
        $this->pdo->exec($SQL);
    }

    public function down(){
        $SQL = "DROP TABLE users;";
        $this->pdo->exec($SQL);
    }
}