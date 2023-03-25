<?php

namespace Source;

use Router\Router;

class App{
    
    public function __construct(private Router $router, private string $uri)
    {
        $this->router = $router;
        $this->uri = $uri;
    }

    public function run(){

        // Reponse de la route
        try {
            echo $this->router->resolve($this->uri);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}