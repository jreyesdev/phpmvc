<?php
namespace App\Core;

use App\Core\Middlewares\Middleware;

class Controller{
    /**
     * @var string
     */
    public $layout = 'app';
    /**
     * @var string
     */
    public $action = '';

    /**
     * @var Middleware[]
     */
    protected $middlewares = [];

    /**
     * @param string $layout
     */
    public function setLayout(string $layout){
        $this->layout = $layout;
    }

    public function render(string $view, array $params = []){
        return App::$app->router->renderView($view,$params);
    }

    public function middleware(Middleware $middleware){
        $this->middlewares[] = $middleware;
    }

    /** @return array */
    public function getMiddlewares() : array {
        return $this->middlewares;
    }
}