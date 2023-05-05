<?php

namespace Controllers;

use Source\Twig as SourceTwig;
use Models\CommentModel;

class CommentController
{
    /**
     * Renvoi la vue de validation des commentaires
     * @return
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
    public function createComment($idArticle){
        if(!isset($_POST['comment']) || empty($_POST['comment'])){
            header('Location: ./article?'.$idArticle);
            exit();
        }
        (new CommentModel)->createComment($idArticle, $_SESSION['username'], stripslashes($_POST['comment']));
        header('Location: ./article?' . $idArticle);
        exit();
    }

    /**
     * Suppression d'un commentaire
     * @param $idCommentAndArticle
     */
    public function deleteComment($idCommentAndArticle){
        $idComment = explode('&', $idCommentAndArticle)[0];
        $idArticle = explode('&', $idCommentAndArticle)[1];

        (new CommentModel)->deleteComment($idComment);
        header('Location: ./article?'.$idArticle);
        exit();
    }

    /**
     * Validation d'un commentaire
     * @param $idComment
     */
    public function validComment($idComment){
        (new CommentModel)->validateComment($idComment);
        header('Location: ./validate-comments');
    }

    /**
     * Rejet d'un commentaire
     * @param $idComment
     */
    public function rejectComment($idComment){
        (new CommentModel)->rejectComment($idComment);
        header('Location: ./validate-comments');
    }

    /**
     * Renvoi la vue d'édition d'un commentaire
     * @param $idComment
     * @return string
     */
    public function indexEditComment($idComment){
        $twig = SourceTwig::getTwigEnvironment();
        $comment = (new CommentModel)->getComment($idComment);
        return $twig->render('comment_edit.html.twig', ['comment' => $comment]);
    }

    /**
     * Modification d'un commentaire
     * @param $idCommentAndArticle
     */
    public function modifyComment($idCommentAndArticle){
        $idComment = explode('&', $idCommentAndArticle)[0];
        $idArticle = explode('&', $idCommentAndArticle)[1];
        if(!isset($_POST['content']) || empty($_POST['content'])){
            header('Location: ./article?'.$idArticle);
            exit;
        }
        $content = trim($_POST['content']);
        $content = htmlspecialchars($content);
        (new CommentModel)->modifyComment($idComment, $content);
        header('Location: ./article?'.$idArticle);
    }
}
