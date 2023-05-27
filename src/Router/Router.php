<?php

namespace Router;

use Source\Twig as SourceTwig;

class Router
{
    // Tableau des routes
    private mixed $routes = [];

    // Enregistre une route
    public function register(string $path, mixed $action): void{
        $this->routes[$path] = $action;
    }

    /**
 * Cette fonction résout une URI de requête HTTP en une action à effectuer
 * @param string $uri
 * @return mixed
 */
public function resolve(string $uri): mixed {
    // Récupère le chemin de l'URI
    $path = explode('?', $uri)[0];
    // On supprime tout ce qui se trouve avant notre dossier public
    $path = str_replace("/Blog_OCR/public", "", $path);
    // Teste si la route existe
    $action = $this->routes[$path] ?? null;

    // Si la route existe.
    if (is_array($action)) {
        [$className, $method] = $action;

        // Récupère les arguments de l'URI
        $argument = explode('?', $uri)[1] ?? null;

        // Si la classe existe et que la méthode existe
        if (class_exists($className) && method_exists($className, $method)) {
            // Instanciation de la classe $className
            $class = new $className();

            // Renvoie la valeur de la méthode $method de la classe $class avec les arguments $argument
            return $class->$method($argument);
        }
    }

    // Si la route n'existe pas.
    $twig = SourceTwig::getTwigEnvironment();
    return $twig->render('404.html.twig');
}

}