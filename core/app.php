<?php
namespace App\Core;

use App\Models\User;
use Exception;

/**
 * @package App\Core
 */
class App{
    /**
     * @var string
     */
    public static $ROOT_DIR;
    /**
     * @var App
     */
    public static $app;
    /**
     * @var string
     */
    public $layout = 'app';
    /**
     * @var Router
     */
    public $router;
    /**
     * @var Request
     */
    public $request;
    /**
     * @var Response
     */
    public $response;
    /**
     * @var Controller
     */
    public $controller;
    /**
     * @var Database
     */
    public $db;
    /**
     * @var Session
     */
    public $session;
    /**
     * @var string
     */
    public $userClass;
    /**
     * @var DbModel
     */
    public $user = null;

    public function __construct($rootPath){
        $this->userClass = $this->getConfig()['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request,$this->response);
        $this->session = new Session();
        $this->db = new Database($this->getConfig()['db']);
        $user = $this->session->get('user');
        if($user){
            $this->user = $this->userClass::findOne([
                $this->userClass::primaryKey() => $user
            ]);
        }
    }

    public function run(){
        try{
            echo $this->router->resolve();
        }catch(Exception $e){
            $this->response->status($e->getCode());
            echo $this->router->renderView('errors/error',[
                'exception' => $e
            ]);
        }
    }

    /**
     * @return Controller
     */
    public function getController(){
        return $this->controller;
    }

    /**
     * @param Controller $controller
     * @return void
     */
    public function setController(Controller $controller){
        $this->controller = $controller;
    }

    /**
     * @return array
     */
    protected function getConfig(){
        return [
            'userClass' => User::class,
            'db' => [
                'host' => $_ENV['DB_HOST'],
                'port' => $_ENV['DB_PORT'],
                'database' => $_ENV['DB_DATABASE'],
                'username' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD']
            ]
        ];
    }

    /**
     * Login user
     * @param DbModel $user
     * @return bool
     */
    public function login(DbModel $user){
        $this->user = $user;
        $this->session->set('user',$user->{$user->primaryKey()});
        return true;
    }

    /**
     * Logout user
     */
    public function logout(){
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest(){
        return !self::$app->user;
    }
}