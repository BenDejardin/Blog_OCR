<?php

namespace Router;

use Source\Twig as SourceTwig;

class Router
{
    private array $routes = [];
    
    // Enregistre une route
    public function register(string $path, array $action): void{
        $this->routes[$path] = $action;
    } 

    // Cette fonction résout une URI de requête HTTP en une action à effectuer
    public function resolve(string $uri): mixed {

        // Récupère le chemin de l'URI
        $path = explode ('?', $uri)[0];
        // Ont supprime tous ce qu'il y a avant notre dossier public
        $path = str_replace("/BlogOCR/public", "", $path);
        // Test si la route existe
        $action = $this->routes[$path] ?? null;

        // Si la route existe
        if(is_array($action)){

            // $class = $action[0]; $method = $action[1];
            [$className, $method] = $action;

            // Récupère les arguments de l'URI
            $argument = explode ('?', $uri)[1] ?? null;

            // Si la classe existe et que la méthode existe
            if(class_exists($className) && method_exists($className, $method)){
                
                // Instanciation de la classe $className
                $class = new $className();

                // Renvoi la valeur de la méthode $method de la classe $class avec les attributs $argument
                return call_user_func_array([$class, $method], [$argument]);
            }
        }
        // Si la route n'existe pas
        $twig = SourceTwig::getTwigEnvironment();
        return $twig->render('404.html.twig');
        
    }
}