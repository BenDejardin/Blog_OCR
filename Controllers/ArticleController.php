<?php

namespace Controllers;

use Models\ArticleModel;
use Source\Twig as SourceTwig;
use Models\CommentModel;

class ArticleController
{
    private function getTwig()
    {
        return SourceTwig::getTwigEnvironment();
    }

    private function getArticle($id, $isAdmin)
    {
        $article = (new ArticleModel)->getArticle($id);
        if ($article == null) {
            return $this->getTwig()->render('404.html.twig');
        }
        $comments = (new CommentModel)->getComments($id);
        foreach ($comments as $comment){
            $comment->content = str_replace("&#039;", "'", $comment->content);
        }
        $template = ($isAdmin) ? 'article_admin.html.twig' : 'article.html.twig';
        return $this->getTwig()->render($template, ['article' => $article,'comments' => $comments]);
    }

    public function getArticles($isAdmin)
    {
        $articles = (new ArticleModel)->getArticles();
        $template = ($isAdmin) ? 'articles_admin.html.twig' : 'articles.html.twig';
        return $this->getTwig()->render($template, ['articles' => $articles]);
    }

    public function getArticlesUser()
    {
        return $this->getArticles(false);
    }

    public function getArticlesAdmin()
    {
        if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        return $this->getArticles(true);
    }

    public function getArticleUser($id)
    {
        return $this->getArticle($id, false);
    }

    public function addArticle()
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        return $this->getTwig()->render('create_article.html.twig');
    }

    public function createArticle()
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        $article = (new ArticleModel)->createArticle($_POST['title'], $_POST['subtitle'], $_POST['content']);
        header('Location: ./articles-admin');
        exit();
    }

    public function deleteArticle($id)
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        (new ArticleModel)->delete($id);
        header('Location: ./articles-admin');
        exit();
    }

    public function editArticle($id)
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        $article = (new ArticleModel)->getArticle($id);
        return $this->getTwig()->render('edit_article.html.twig', ['article' => $article]);
    }

    public function editArticle2($id)
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        (new ArticleModel)->updateArticle($id, $_POST['title'], $_POST['subtitle'], $_POST['content']);
        header('Location: ./articles-admin');
        exit();
    }

    public function createComment($id){
        (new CommentModel)->createComment($id, $_SESSION['username'], $_POST['comment']);
        header('Location: ./article?'.$id);
        exit();
    }

    public function deleteComment($idCommentAndArticle){
        $idComment = explode('&', $idCommentAndArticle)[0];
        $idArticle = explode('&', $idCommentAndArticle)[1];
        
        (new CommentModel)->deleteComment($idComment);
        header('Location: ./article?'.$idArticle);
        exit();
    }
}
