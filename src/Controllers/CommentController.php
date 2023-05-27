<?php

namespace Controllers;

use Models\ArticleModel;
use Source\Twig as SourceTwig;
use Models\CommentModel;

class CommentController
{
    /**
     * Renvoi la vue de validation des commentaires
     * @return string
     */
    public function index(){
        $twig = SourceTwig::getTwigEnvironment();
        $comments = (new CommentModel)->getCommentsToValidate();
        return $twig->render('comment_valid.html.twig', ['comments' => $comments]);
    }

    /**
     * Création d'un commentaire
     * @param $idArticle
     * @return string
     */
    public function createComment(int $idArticle)
    {
        $comment = trim($_POST['comment']);
        $errors = [];
        $minLength = 5;
        $maxLength = 200;

        if (empty($comment)) {
            $errors['comment'] = 'Le commentaire ne peut pas être vide.';
        }
        elseif (strlen($comment) < $minLength || strlen($comment) > $maxLength) {
            $errors['comment'] = 'Le commentaire doit contenir entre '.$minLength.' et '.$maxLength.' caractères.';
        }
        if (!empty($errors)) {
            $twig = SourceTwig::getTwigEnvironment();
            // Obtenez l'article correspondant au $idArticle
            $article = (new ArticleModel)->getArticle($idArticle);
            $comments = (new CommentModel)->getComments($idArticle);
            return $twig->render('article.html.twig', ['article' => $article, 'comments'=> $comments , 'errors' => $errors]);
        }
        (new CommentModel)->createComment($idArticle, $_SESSION['username'], stripslashes($comment));
        header('Location: ./article?' . $idArticle);
        exit();
    }

    /**
     * Suppression d'un commentaire
     * @param $idCommentAndArticle
     * @return string 
     */
    public function deleteComment(string $idCommentAndArticle){
        $idComment = intval(explode('&', $idCommentAndArticle)[0]);
        $idArticle = intval(explode('&', $idCommentAndArticle)[1]);

        (new CommentModel)->deleteComment($idComment);
        header('Location: ./article?'.$idArticle);
        exit();
    }

    /**
     * Validation d'un commentaire
     * @param $idComment
     * @return void
     */
    public function validComment(int $idComment){
        (new CommentModel)->validateComment($idComment);
        header('Location: ./validate-comments');
    }

    /**
     * Rejet d'un commentaire
     * @param $idComment
     * @return void
     */
    public function rejectComment(int $idComment){
        (new CommentModel)->rejectComment($idComment);
        header('Location: ./validate-comments');
    }

    /**
 * Renvoi la vue d'édition d'un commentaire
 * @param $idComment
 * @return string
 */
public function indexEditComment(int $idComment) {
    $twig = SourceTwig::getTwigEnvironment();
    $comment = (new CommentModel)->getComment($idComment);
    $errors = $_SESSION['errors'] ?? []; // Récupérer les erreurs s'il y en a
    $content = $_SESSION['post_data']['content'] ?? $comment->content ?? ''; // Récupérer la valeur postée ou la valeur de la base de données ou une chaîne vide
    unset($_SESSION['errors'], $_SESSION['post_data']); // Supprimer les données de session
    return $twig->render('comment_edit.html.twig', ['comment' => $comment, 'errors' => $errors, 'content' => $content]);
}

/**
 * Modification d'un commentaire
 * @param $idCommentAndArticle
 * @return void
 */
public function modifyComment(string $idCommentAndArticle) {
    $idComment = intval(explode('&', $idCommentAndArticle)[0]);
    $idArticle = intval(explode('&', $idCommentAndArticle)[1]);
    $content = trim($_POST['content']);
    if (empty($content)) {
        $_SESSION['errors']['content'] = 'Veuillez saisir le commentaire.';
        $_SESSION['post_data'] = ['content' => $content];
        header('Location: ./edit-comment?'.$idComment);
        exit();
    }
    elseif (mb_strlen($content) < 5) {
        $_SESSION['errors']['content'] = 'Le commentaire doit contenir au moins 5 caractères.';
        $_SESSION['post_data'] = ['content' => $content];
        header('Location: ./edit-comment?'.$idComment);
        exit();
    }
    elseif (mb_strlen($content) > 300) {
        $_SESSION['errors']['content'] = 'Le commentaire doit contenir moins de 300 caractères.';
        $_SESSION['post_data'] = ['content' => $content];
        header('Location: ./edit-comment?'.$idComment);
        exit();
    }
    $content = htmlspecialchars($content);
    (new CommentModel)->modifyComment($idComment, $content);
    header('Location: ./article?'.$idArticle);
    exit();
}




}
