<?php

namespace Users;

use Exception\ExceptionUser;
use Exception\ExceptionUserBanni;

require_once('../Exception/ExceptionUser.php');
require_once('../Exception/ExceptionUserBanni.php');

class Login {

    public function __construct(public User $user) {


    }

    public function login(){

        if(!$this->user->Verified())
        { Throw new ExceptionUser;}

        if($this->user->isBanni()){
            Throw new ExceptionUserBanni();

        }
    }
}