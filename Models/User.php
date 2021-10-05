<?php
namespace App\Models;

use App\Core\DbModel;

class User extends DbModel{
    /**
     * @var string
     */
    public $firstname = '';
    /**
     * @var string
     */
    public $lastname = '';
    /**
     * @var string
     */
    public $email = '';
    /**
     * @var string
     */
    public $password = '';
    /**
     * @var string
     */
    public $password_confirm = '';
    /**
     * @var string
     */
    public $deleted_at;

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
        return ['firstname','lastname','email','password','deleted_at'];
    }

    /**
     * @return array
     */
    public function labels() : array{
        return [
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'password_confirm' => 'Confirm password',
            'deleted_at' => 'Deleted at',
        ];
    }

    /**
     * Rules
     * @return array
     */
    public function rules() : array{
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [
                self::RULE_REQUIRED,
                self::RULE_EMAIL,
                [self::RULE_UNIQUE,'class'=>self::class]
            ],
            'password' => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min' => 8]
            ],
            'password_confirm' => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, 'match' => 'password']
            ],
        ];
    }
    /**
     * Create nuew user
     */
    public function create(){
        $this->deleted_at = NULL;
        $this->password = password_hash($this->password,PASSWORD_DEFAULT);
        return $this->save();
    }
}