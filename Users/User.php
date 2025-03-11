<?php

namespace Users;

use Exception\ExceptionUser;

class User{

public string $firstName ;
public string $lastName;

public function __construct($firstName,$lastName) {

    $this->firstName=$firstName;
    $this->lastName=$lastName;
}

public function Verified():bool {
if( $this->firstName == 'Kamal')
    return true;
else return false;
 
}


public function isBanni():bool {

if( $this->firstName == 'Jaffar') {
    return true;
}
    
}

}