<?php 

namespace Controllers;

use Source\Twig as SourceTwig;

class HomeController
{
    // Test ou message = ....?message et peut etre null
    public function index($message = null) 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        // Pour test : Si $message est null alors $message = 'Home page'
        if ($message === null) {
            $message = 'Home page';
        }

        // Renvoi la vue home.html.twig avec le message
        return $twig->render('home.html.twig', ['name' => $message]);
        
    }
}