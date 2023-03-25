<?php

use Router\Router;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Source\App;

include '../vendor/autoload.php';

// Définition du chemin vers les vues
define('BASE_VIEW_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR. 'views' .DIRECTORY_SEPARATOR);

// Instanciation du routeur
$router = new Router();

// Enregistrement des routes
$router->register('/home', ['Controllers\HomeController', 'index']);
$router->register('/articles', ['Controllers\ArticleController', 'getAllArticle']);
$router->register('/article', ['Controllers\ArticleController', 'getArticle']);

(new App($router, $_SERVER['REQUEST_URI']))->run();