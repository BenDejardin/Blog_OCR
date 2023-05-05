<?php

namespace Controllers;

use Models\ArticleModel;
use Source\Twig as SourceTwig;
use Models\CommentModel;

class ArticleController
{
    /**
     * Renvoi l'environnement Twig
     * @return \Twig\Environment
     */
    private function getTwig()
    {
        return SourceTwig::getTwigEnvironment();
    }

    /**
     * Renvoi un article
     * @param $idArticle
     * @param $isAdmin
     * @return string
     */
    private function getArticle($idArticle, $isAdmin)
    {
        $article = (new ArticleModel)->getArticle($idArticle);
        if ($article == null) {
            return $this->getTwig()->render('404.html.twig');
        }
        $comments = (new CommentModel)->getComments($idArticle);
        foreach ($comments as $comment){
            $comment->content = str_replace("&#039;", "'", $comment->content);
        }
        $template = ($isAdmin) ? 'article_admin.html.twig' : 'article.html.twig';
        return $this->getTwig()->render($template, ['article' => $article,'comments' => $comments]);
    }

    /**
     * Renvoi la vue listant les articles pour un utilisateur
     * Admin => Vue pour la modification des articles
     * User => Vue pour la lecture des articles
     * @param $idArticle
     * @return string
     */
    public function getArticles($isAdmin)
    {
        $articles = (new ArticleModel)->getArticles();
        $template = ($isAdmin) ? 'articles_admin.html.twig' : 'articles.html.twig';
        return $this->getTwig()->render($template, ['articles' => $articles]);
    }

    /**
     * Fonction appeler par le router pour afficher les articles pour un user
     * @return string
     */
    public function getArticlesUser()
    {
        return $this->getArticles(false);
    }

    /**
     * Fonction appeler par le router pour afficher les articles pour un admin
     * @return string
     */
    public function getArticlesAdmin()
    {
        if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        return $this->getArticles(true);
    }

    /**
     * Fonction appeler par le router pour afficher un article pour un user
     * @param $idArticle
     * @return string
     */
    public function getArticleUser($idArticle)
    {
        return $this->getArticle($idArticle, false);
    }

    /**
     * Verifie si l'utilisateur est admin et le redirige vers la page d'edition de l'article
     * @return string
     */
    public function addArticle()
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        return $this->getTwig()->render('create_article.html.twig');
    }

    /**
     * Verifie si l'utilisateur est admin et ajoute l'article
     * @return string
     */
    public function createArticle()
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        if(!isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['subtitle']) || empty($_POST['subtitle']) || !isset($_POST['content']) || empty($_POST['content'])){
            header('Location: ./add-article');
            exit();
        }
        (new ArticleModel)->createArticle($_POST['title'], $_POST['subtitle'], $_POST['content']);
        header('Location: ./articles-admin');
        exit();
    }

    /**
     * Verifie si l'utilisateur est admin et supprime l'article
     * @param $idArticle
     * @return string
     */
    public function deleteArticle($idArticle)
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        (new ArticleModel)->delete($idArticle);
        header('Location: ./articles-admin');
        exit();
    }

    /**
     * Verifie si l'utilisateur est admin et le redirige vers la page d'edition de l'article
     * @param $idArticle
     * @return string
     */
    public function editArticle($idArticle)
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        $article = (new ArticleModel)->getArticle($idArticle);
        return $this->getTwig()->render('edit_article.html.twig', ['article' => $article]);
    }

    /**
     * Verifie si l'utilisateur est admin et modifie l'article
     * @param $idArticle
     * @return string
     */
    public function editArticle2($idArticle)
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        if(!isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['subtitle']) || empty($_POST['subtitle']) || !isset($_POST['content']) || empty($_POST['content'])){
            header('Location: ./edit-article?'.$idArticle);
            exit();
        }
        (new ArticleModel)->updateArticle($idArticle, $_POST['title'], $_POST['subtitle'], $_POST['content']);
        header('Location: ./articles-admin');
        exit();
    }
}
