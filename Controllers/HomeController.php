<?php
namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Request;

class HomeController extends Controller{
    public function home(){
        $params = [
            'name' => App::$app->user->firstname ?? 'Guest'
        ];
        return view('home',$params);
    }
    public function contact(){
        return view('contact');
    }
    public function handleContact(Request $req){
        printr($req->getBody());
        return 'Handle form';
    }
}