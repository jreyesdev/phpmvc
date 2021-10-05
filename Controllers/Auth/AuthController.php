<?php
namespace App\Controllers\Auth;

use App\Core\App;
use App\Core\Controller;
use App\Core\Middlewares\AuthMiddleware;
use App\Core\Request;
use App\Models\User;
use App\Models\UserLogin;

class AuthController extends Controller{
    public function __construct(){
        $this->middleware(
            new AuthMiddleware(['profile','register','logout'])
        );
    }
    public function login(){
        return view('auth/login',[
            'model' => new UserLogin()
        ]);
    }
    public function loginPost(Request $req){
        $user = new UserLogin();
        $user->loadData($req->getBody());
        if($user->validate() && $user->login()){
            App::$app->session->setFlash('success','Login successfully');
            return redirect('/');
        }
        return view('auth/login',[
            'model' => $user
        ]);
    }
    public function register(Request $req){
        return view('auth/register',[
            'model' => new User()
        ]);
    }
    public function registerPost(Request $req){
        $user = new User();
        $user->loadData($req->getBody());
        if($user->validate() && $user->create()){
            App::$app->session->setFlash('success','Registered user successfully');
            return redirect('/');
        }
        return view('auth/register',[
            'model' => $user
        ]);
    }
    public function logout(Request $req){
        App::$app->logout();
        App::$app->session->setFlash('success','Logout successfully');
        return redirect('/');
    }
    public function profile(){
        return view('auth/profile',[
            'user' => App::$app->user
        ]);
    }
}