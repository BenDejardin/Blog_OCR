<?php 

namespace Controllers;

use Models\ArticleModel;
use Source\Twig as SourceTwig;

class ArticleController
{
    public function getAllArticle() 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        // Recupere les articles de la base de données
        $Article = new ArticleModel; //ligne 16
        $articles = $Article->getArticles();

        // Renvoi la vue article.html.twig avec le nbArticle
        return $twig->render('articles.html.twig', ['articles' => $articles]);
        
    }


    public function getArticle($id) 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        // Recupere les articles de la base de données
        $Article = new ArticleModel;
        $article = $Article->getArticle($id);

        // Renvoi la vue article.html.twig avec le nbArticle
        return $twig->render('article.html.twig', ['article' => $article]);
        
    }
}