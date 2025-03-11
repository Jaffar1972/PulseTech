<?php 

namespace Vehicle;

use Exception\ExceptionUserVehicle;

require_once('../Vehicle/Peugeot.php');
require_once('../Exception/ExceptionUserVehicle.php');

class Autorisation {

public function __construct(public Peugeot $peugeot){


}

public function controle(){

    if(!$this->peugeot->Verified())

    { Throw new ExceptionUserVehicle; }

    else echo 'Véhicule en bonne état ';
}



}