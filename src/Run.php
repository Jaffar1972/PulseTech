<?php

namespace src;

use Routes\Route;

class Run {
    public function __construct(private Route $router, private String $uri ){}

    public function run(){
    try {
      
        $result = $this->router->resolve($this->uri);
        echo $result;
    } catch (\Exception $e) {
        echo $e->getMessage();}

    }
}