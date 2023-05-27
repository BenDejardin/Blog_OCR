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
    private function getArticle(int $idArticle, bool $isAdmin)
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
    public function getArticles(bool $isAdmin)
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
    public function getArticleUser(int $idArticle)
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
        $errors = []; // Tableau pour stocker les erreurs

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des champs du formulaire
            if (empty($_POST['title'])) {
                $errors['title'] = 'Veuillez entrer un titre.';
            } elseif (strlen($_POST['title']) < 5 || strlen($_POST['title']) > 50) {
                $errors['title'] = 'Le titre doit contenir entre 5 et 50 caractères.';
            }

            if (empty($_POST['subtitle'])) {
                $errors['subtitle'] = 'Veuillez entrer un sous-titre.';
            } elseif (strlen($_POST['subtitle']) < 5 || strlen($_POST['subtitle']) > 50) {
                $errors['subtitle'] = 'Le sous-titre doit contenir entre 5 et 50 caractères.';
            }

            if (empty($_POST['content'])) {
                $errors['content'] = 'Veuillez entrer le contenu de l\'article.';
            } elseif (strlen($_POST['content']) < 10 || strlen($_POST['content']) > 500) {
                $errors['content'] = 'Le contenu de l\'article doit contenir entre 10 et 500 caractères.';
            }

            // Si aucune erreur de validation n'a été détectée
            if (empty($errors)) {
                // Ajouter l'article dans la base de données
                (new ArticleModel)->createArticle($_POST['title'], $_POST['subtitle'], $_POST['content']);

                // Rediriger vers la page de succès ou la page suivante
                header('Location: ./articles-admin');
                exit();
            }
        }

        // Renvoi de la vue create_article.html.twig avec les erreurs et les entrées utilisateur
        return $this->getTwig()->render('create_article.html.twig', ['errors' => $errors, 'title' => $_POST['title'] ?? '', 'subtitle' => $_POST['subtitle'] ?? '', 'content' => $_POST['content'] ?? '']);
    }


    /**
     * Verifie si l'utilisateur est admin et supprime l'article
     * @param $idArticle
     * @return string
     */
    public function deleteArticle(int $idArticle)
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
    public function editArticle(int $idArticle)
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
    public function editArticle2(int $idArticle)
    {
        if ($_SESSION['isAdmin'] != 1) {
            return $this->getTwig()->render('not_admin.html.twig');
        }
        
        $article = (new ArticleModel)->getArticle($idArticle);
        
        $errors = [];
        $formData = [];
        $minLength = 5;
        $maxLength = 100;
        $minLengthContent = 10;
        $maxLengthContent = 5000;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData['title'] = trim($_POST['title']);
            $formData['subtitle'] = trim($_POST['subtitle']);
            $formData['content'] = trim($_POST['content']);

            if (empty($formData['title'])) {
                $errors['title'] = 'Le titre ne peut pas être vide.';
            }

            elseif (strlen($formData['title']) < $minLength || strlen($formData['title']) > $maxLength) {
                $errors['title'] = 'Le titre doit contenir entre '.$minLength.' et '.$maxLength.' caractères.';
            }

            if (empty($formData['subtitle'])) {
                $errors['subtitle'] = 'Le sous-titre ne peut pas être vide.';
            }

            elseif (strlen($formData['subtitle']) < $minLength || strlen($formData['subtitle']) > $maxLength) {
                $errors['subtitle'] = 'Le sous-titre doit contenir entre '.$minLength.' et '.$maxLength.' caractères.';
            }

            if (empty($formData['content'])) {
                $errors['content'] = 'Le contenu ne peut pas être vide.';
            }

            elseif (strlen($formData['content']) < $minLengthContent || strlen($formData['content']) > $maxLengthContent) {
                $errors['content'] = 'Le contenu doit contenir entre '.$minLengthContent.' et '.$maxLengthContent.' caractères.';
            }

            if (empty($errors)) {
                (new ArticleModel)->updateArticle($idArticle, $formData['title'], $formData['subtitle'], $formData['content']);
                header('Location: ./articles-admin');
                exit();
            }
        }
        return $this->getTwig()->render('edit_article.html.twig', ['article' => $article, 'errors' => $errors, 'formData' => $formData]);
    }
}
