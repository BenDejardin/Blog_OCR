<?php 

namespace Controllers;


use Models\DB;
use Source\Twig as SourceTwig;

class ArticleController
{
    public function getAllArticle() 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        // Recupere les articles de la base de données
        $db = new DB();
        $articles = $db->all('blog_posts');

        // Renvoi la vue article.html.twig avec le nbArticle
        return $twig->render('articles.html.twig', ['articles' => $articles]);
        
    }


    public function getArticle($id) 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        // Recupere les articles de la base de données
        $db = new DB();
        $article = $db->find('blog_posts' , $id);

        // Renvoi la vue article.html.twig avec le nbArticle
        return $twig->render('article.html.twig', ['article' => $article]);
        
    }
}