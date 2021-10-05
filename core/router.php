<?php
namespace App\Core;

/**
 * @package App\Core
 */
class Router{
    /**
     * @var Array
     */
    protected $routes = [];
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Response
     */
    protected $response;

    public function __construct(Request $req, Response $res){
        $this->request = $req;
        $this->response = $res;
    }
    /**
     * Method GET
     * @param string $path
     * @param callback $callback
     * @return void
     */
    public function get(string $path, $callback){
        $this->routes['get'][$path] = $callback;
    }
    /**
     * Method POST
     * @param string $path
     * @param callback $callback
     * @return void
     */
    public function post(string $path, $callback){
        $this->routes['post'][$path] = $callback;
    }
    /**
     * @return void
     */
    public function resolve(){
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $call = $this->routes[$method][$path] ?? false;
        if(!$call){
            $this->response->status(404);
            return $this->renderView('errors/404');
        }
        if(is_string($call)){
            return $this->renderView($call);
        }
        if(is_array($call)){
            /** @var Controller $controller */
            $controller = new $call[0]();
            $controller->action = $call[1];
            App::$app->controller = $controller;
            $call[0] = $controller;
            foreach($controller->getMiddlewares() as $mid){
                $mid->execute();
            }
        }
        return call_user_func($call, $this->request);
    }
    /**
     * Render View
     * @param string $view
     * @return string
     */
    public function renderView(string $view, array $params = []){
        $layout = $this->layoutContent();
        $content = $this->viewContent($view,$params);
        return str_replace('{{content}}',$content,$layout);
    }
    /**
     * Render content in main view
     * @param string $content
     * @return string
     */
    public function renderContent(string $content){
        $layout = $this->layoutContent();
        return str_replace('{{content}}',$content,$layout);
    }
    /**
     * Content layout
     */
    protected function layoutContent(){
        $layout = App::$app->controller->layout ?? App::$app->layout;
        ob_start();
        include_once App::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }
    /**
     * Render only view
     */
    protected function viewContent($view,$params){
        foreach($params as $k => $v){
            $$k = $v;
        }
        ob_start();
        include_once App::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}