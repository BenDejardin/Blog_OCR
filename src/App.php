<?php

namespace Source;

use Router\Router;

class App{

    /**
     * App constructor.
     * @param Router $router
     * @param string $uri
     */
    public function __construct(private Router $router, private string $uri)
    {
        $this->router = $router;
        $this->uri = $uri;
    }

    /**
     * Lance l'application
     * @return void
     */
    public function run(){

        // Reponse de la route
        try {
            echo $this->router->resolve($this->uri);
        } catch (\Exception $e) {
    echo htmlspecialchars($e->getMessage());
}
    }
}