<?php
namespace App\Core;

abstract class Model{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MAX = 'max';
    public const RULE_MIN = 'min';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @return array
     */
    public function labels() : array {
        return [];
    }

    /**
     * @return string
     */
    public function getLabel(string $attr) : string {
        return $this->labels()[$attr] ?? $attr;
    }

    /**
     * @return array
     */
    abstract public function rules(): array;

    /**
     * @param array $data
     */
    public function loadData(array $data){
        foreach($data as $k => $val){
            if(property_exists($this,$k)){
                $this->{$k} = $val;
            }
        }
    }

    /**
     * @return bool
     */
    public function validate(){
        foreach($this->rules() as $attr => $rules){
            $value = $this->{$attr};
            foreach($rules as $rule){
                $ruleName = !is_string($rule) ? $rule[0] : $rule;
                if($ruleName === self::RULE_REQUIRED && !$value){
                    $this->addErrorForRules($attr,self::RULE_REQUIRED);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($value,FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRules($attr,self::RULE_EMAIL);
                }
                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']){
                    $this->addErrorForRules($attr,self::RULE_MIN,$rule);
                }
                if($ruleName === self::RULE_MAX && strlen($value) > $rule['max']){
                    $this->addErrorForRules($attr,self::RULE_MAX,$rule);
                }
                if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}){
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRules($attr,self::RULE_MATCH,$rule);
                }
                if($ruleName === self::RULE_UNIQUE){
                    $class = $rule['class'];
                    $unique = $rule['attr'] ?? $attr;
                    $tableName = $class::tableName();
                    $q = "SELECT * FROM $tableName WHERE $unique=:uni;";
                    $stmt = App::$app->db->pdo->prepare($q);
                    $stmt->bindValue(":uni",$value);
                    $stmt->execute();
                    if($stmt->fetchObject()){
                        $this->addErrorForRules($attr,self::RULE_UNIQUE,['field' => $this->getLabel($attr)]);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    /**
     * Add errors
     * @param string $attr
     * @param string $rule
     */
    private function addErrorForRules(string $attr, string $rule, array $params = []){
        $msg = $this->errorsMessages()[$rule] ?? '';
        foreach($params as $k => $val){
            $msg = str_replace("{{$k}}",$val,$msg);
        }
        $this->errors[$attr][] = $msg;
    }

    /**
     * Add errors
     * @param string $attr
     * @param string $message
     */
    public function addError(string $attr, string $message){
        $this->errors[$attr][] = $message;
    }

    /**
     * @return array
     */
    public function errorsMessages(){
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists',
        ];
    }

    /**
     * @param string $attr
     * @return bool
     */
    public function hasError(string $attr){
        return $this->errors[$attr] ?? false;
    }

    /**
     * @param string $attr
     * @return string
     */
    public function getFirstError(string $attr){
        return $this->errors[$attr][0] ?? false;
    }
}