<?php

namespace Controllers;


use Source\Twig as SourceTwig;
use Models\CommentModel;

class CommentController
{
    public function index(){
        $twig = SourceTwig::getTwigEnvironment();
        $comments = (new CommentModel)->getCommentsToValidate();
        return $twig->render('comment_valid.html.twig', ['comments' => $comments]);
    }

    public function validComment($id){
        (new CommentModel)->validateComment($id);
        header('Location: ./validate-comments');
    }

    public function rejectComment($id){
        (new CommentModel)->rejectComment($id);
        header('Location: ./validate-comments');
    }

    public function indexEditComment($id){
        $twig = SourceTwig::getTwigEnvironment();
        $comment = (new CommentModel)->getComment($id);
        return $twig->render('comment_edit.html.twig', ['comment' => $comment]);
    }

    public function modifyComment($idCommentAndArticle){
        $idComment = explode('&', $idCommentAndArticle)[0];
        $idArticle = explode('&', $idCommentAndArticle)[1];
        $content = trim($_POST['content']);
        $content = htmlspecialchars($content);
        (new CommentModel)->modifyComment($idComment, $content);
        header('Location: ./article?'.$idArticle);
    }
}
