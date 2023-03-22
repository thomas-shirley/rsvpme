<?php

class hashPassword {

    public string $userPassword;
    public function __construct($userPassword) {
        $this->userPassword = $userPassword;  
    }

    public function hashPassword(){
        $algos =  password_algos();
        return password_hash($this->userPassword, $algos[2]);
    }

    
    
}

$newPass = new hashPassword('your-password-here');
echo $newPass->hashPassword();
