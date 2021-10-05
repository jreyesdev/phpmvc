<?php

class AddPassColumn{
    public $pdo;
    public function __construct(){
        $this->pdo = \App\Core\App::$app->db->pdo;
    }
    public function up(){
        $SQL = "ALTER TABLE users ADD COLUMN password VARCHAR(512) NOT NULL;";
        $this->pdo->exec($SQL);
    }

    public function down(){
        $SQL = "ALTER TABLE users DROP COLUMN password;";
        $this->pdo->exec($SQL);
    }
}