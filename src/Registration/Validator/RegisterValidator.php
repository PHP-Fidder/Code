<?php
declare(strict_types=1);

namespace PhpFidder\Core\Registration\Validator;

final class RegisterValidator
{
    private array $errors = [];
    public function isValid(string $username,string $email, string $password, string $passwordRepeat):bool
    {
        if(mb_strlen($username) === 0){
            $this->errors[]='Username is empty';
        }
        if(mb_strlen($username) <= 3){
            $this->errors[] = 'Username is too short';
        }
        if(mb_strlen($username) > 20){
            $this->errors[] = 'Username is too long';
        }
        if(mb_strlen($email) === 0){
            $this->errors[]='Email is empty';
        }
        if(filter_var($email,FILTER_VALIDATE_EMAIL) === false){
            $this->errors[]= 'Email is invalid';
        }
        if(mb_strlen($password) === 0){
            $this->errors[]='Password is empty';
        }
        if(mb_strlen($password) < 8){
            $this->errors[] = 'Password is too short';
        }
        if($password !== $passwordRepeat){
            $this->errors[] = 'Password repeat does not match password';
        }

        return count($this->errors) === 0;
    }
    public function getErrors():array
    {
        return $this->errors;
    }
}