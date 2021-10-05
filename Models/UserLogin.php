<?php
namespace App\Models;

use App\Core\App;
use App\Core\DbModel;

class UserLogin extends DbModel{
    /**
     * @var string
     */
    public $email = '';
    /**
     * @var string
     */
    public $password = '';

    /** @return string */
    public function tableName() : string{
        return 'users';
    }

    /** @return string */
    public function primaryKey() : string{
        return 'id';
    }

    /** @return array */
    public function attributes() : array{
        return ['email','password'];
    }

    /**
     * @return array
     */
    public function labels() : array{
        return [
            'email' => 'Email',
            'password' => 'Password'
        ];
    }

    /**
     * Rules
     * @return array
     */
    public function rules() : array{
        return [
            'email' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
            ],
            'password' => [self::RULE_REQUIRED]
        ];
    }
    /**
     * Create nuew user
     */
    public function login(){
        $user = User::findOne([
            'email' => $this->email
        ]);
        if(!$user){
            $this->addError('email','User does not exist with this email');
            return false;
        }
        if(!password_verify($this->password,$user->password)){
            $this->addError('password','Password is incorrect');
            return false;
        }
        return App::$app->login($user);
    }
}