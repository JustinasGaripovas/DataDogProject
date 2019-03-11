<?php
class User{
    public $user_email;
    public $user_password;

    public function __construct($e, $p){
        $this->user_email = $e;
        $this->user_password = password_hash($p, PASSWORD_DEFAULT);

    }
}
