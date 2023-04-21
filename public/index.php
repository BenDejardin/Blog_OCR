<?php

use Router\Router;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Source\App;

include '../vendor/autoload.php';

// Définition du chemin vers les vues
define('BASE_VIEW_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR. 'views' .DIRECTORY_SEPARATOR);

session_start();

// Instanciation du routeur
$router = new Router();

// Enregistrement des routes
$router->register('/', ['Controllers\HomeController', 'index']);
$router->register('/home', ['Controllers\HomeController', 'index']);
$router->register('/articles', ['Controllers\ArticleController', 'getArticlesUser']);
$router->register('/article', ['Controllers\ArticleController', 'getArticleUser']);
$router->register('/login', ['Controllers\LoginController', 'indexLogin']);
$router->register('/login-verif', ['Controllers\LoginController', 'login']);
$router->register('/register', ['Controllers\LoginController', 'indexRegister']);
$router->register('/register-verif', ['Controllers\LoginController', 'register']);
$router->register('/logout', ['Controllers\LoginController', 'logout']);
$router->register('/articles-admin', ['Controllers\ArticleController', 'getArticlesAdmin']);
$router->register('/add-article', ['Controllers\ArticleController', 'addArticle']);
$router->register('/add-article-verif', ['Controllers\ArticleController', 'createArticle']);
$router->register('/delete-article', ['Controllers\ArticleController', 'deleteArticle']);
$router->register('/edit-article', ['Controllers\ArticleController', 'editArticle']);
$router->register('/edit-article-verif', ['Controllers\ArticleController', 'editArticle2']);
$router->register('/add-comment', ['Controllers\ArticleController', 'createComment']);
$router->register('/delete-comment', ['Controllers\ArticleController', 'deleteComment']);
$router->register('/validate-comments', ['Controllers\CommentController', 'index']);
$router->register('/valid-comment', ['Controllers\CommentController', 'validComment']);
$router->register('/reject-comment', ['Controllers\CommentController', 'rejectComment']);
$router->register('/edit-comment', ['Controllers\CommentController', 'indexEditComment']);
$router->register('/edit-comment-verif', ['Controllers\CommentController', 'modifyComment']);
$router->register('/contact', ['Controllers\HomeController', 'contact']);


(new App($router, $_SERVER['REQUEST_URI']))->run();