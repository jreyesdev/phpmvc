<?php
namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

abstract class DbModel extends Model{
    abstract public function tableName(): string;
    abstract public function primaryKey(): string;
    abstract public function attributes(): array;
    /** @var PDO */
    public static $pdo;
    /** @var PDOStatement */
    public $stmt;

    public function __construct(){
        self::$pdo = App::$app->db->pdo;
    }

    public function save(){
        $tableName = $this->tableName();
        $attr = $this->attributes();
        $params = array_map(function($p){ return ":$p"; },$attr);
        $q = "INSERT INTO $tableName (";
        $q .= implode(',',$attr).") VALUES (";
        $q .= implode(',',$params).");";
        $this->stmt = self::$pdo->prepare($q);
        foreach($attr as $a){
            $this->stmt->bindValue(":$a",$this->{$a});
        }
        return $this->ejecuta();
    }

    protected function ejecuta(){
        try{
            return $this->stmt->execute();
        }catch(PDOException $e){
            throw $e;
        }
    }

    /**
     * @param array $data
     * @return DbModel
     */
    public static function findOne(array $data){
        $table = static::tableName();
        $attrKeys = array_keys($data);
        $where = array_map(function($attr){ return "$attr=:$attr"; },$attrKeys);
        $where = implode(' AND ',$where);
        $stmt = self::prepare("SELECT * FROM $table WHERE ".$where);
        foreach($data as $k => $val){
            $stmt->bindValue(":$k",$val);
        }
        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }

    /**
     * @param string $sql
     * @return PDOStatement
     */
    public static function prepare(string $sql){
        return App::$app->db->pdo->prepare($sql);
    }
}