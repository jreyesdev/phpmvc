<?php
namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database{
    /**
     * @var PDO
     */
    public $pdo;

    /**
     * @param array $config
     */
    public function __construct(array $config){
        try {
            $dsn = 'mysql:host='.$config['host'].';port='.$config['port'];
            $dsn .= ';dbname='.$config['database'].';charset=utf8mb4';
            $this->pdo = new PDO(
                $dsn,
                $config['username'],
                $config['password']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function applyMigrations(){
        $this->createMigrationTable();
        $applied = $this->getAppliedMigrations();
        $dir = App::$ROOT_DIR.'/migrations';
        $files = scandir($dir);
        $newMig = [];
        foreach(array_diff($files,$applied) as $mig){
            if($mig === '.' || $mig === '..'){
                continue;
            }

            require_once $dir.'/'.$mig;
            $className = explode('_',pathinfo($mig,PATHINFO_FILENAME))[1];
            $className = ucfirst($className);
            echo "Appliying migration $mig".PHP_EOL;
            (new $className())->up();
            echo "Applied migration $mig".PHP_EOL;
            $newMig[] = $mig;
        }
        if(!empty($newMig)){
            $this->saveMigrations($newMig);
        }else{
            echo "All migrations are applied";
        }
    }
    
    protected function createMigrationTable(){
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;    
        ");
    }

    protected function getAppliedMigrations(){
        $stmt = $this->pdo->prepare("SELECT migration FROM migrations");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function saveMigrations(array $migs){
        $str = implode(',',array_map(function($m){ return "('$m')"; },$migs));
        $stmt = $this->pdo->prepare("INSERT INTO 
            migrations(migration) 
            VALUES $str
        ");
        $stmt->execute();
    }
}