<?php 

namespace Source;

class Twig
{
    private static mixed $instance = null;

    /**
     * Renvoi l'environnement Twig
     * @return \Twig\Environment
     */
    public static function getTwigEnvironment(): \Twig\Environment
    {

        // Ont ne créer qu'une seule instance de Twig / Singleton
        if(self::$instance === null){
            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
            self::$instance = new \Twig\Environment($loader, [
                'cache' => false
            ]);
            self::$instance->addGlobal('isAdmin', $_SESSION['isAdmin'] ?? 0);
            self::$instance->addGlobal('username', $_SESSION['username'] ?? null);
        }
        return self::$instance;
    } 
}
