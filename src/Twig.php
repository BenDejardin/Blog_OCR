<?php 

namespace Source;

class Twig
{
    private static $instance = null;

    public static function getTwigEnvironment(): \Twig\Environment
    {
        // Ont ne créer qu'une seule instance de Twig / Singleton
        if(self::$instance === null){
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
            self::$instance = new \Twig\Environment($loader, [
                'cache' => false,
            ]);
        }
        return self::$instance;
    } 
}