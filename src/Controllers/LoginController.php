<?php 

namespace Controllers;

use Models\LoginModel;
use Source\Twig as SourceTwig;

class LoginController
{
    /**
     * Renvoi la vue Login
     * @return string
     */
    public function indexLogin(?string $username) 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        if($username){
            return $twig->render('login.html.twig', ['username' => $username]);
        }

        // Renvoi la vue login.html.twig
        return $twig->render('login.html.twig');
    }

    /**
     * Connecte l'utilisateur
     * @return string
     */
    public function login()
    {
        $Login = new LoginModel;
        $errors = []; // Tableau pour stocker les erreurs

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des champs du formulaire
            if (!isset($_POST['username']) || empty($_POST['username'])) {
                $errors['username'] = 'Veuillez entrer votre nom d\'utilisateur.';
            }

            if (!isset($_POST['pwd']) || empty($_POST['pwd'])) {
                $errors['pwd'] = 'Veuillez entrer votre mot de passe.';
            }

            // Si aucune erreur de validation n'a été détectée
            if (empty($errors)) {
                $user = $Login->login($_POST['username']);
                if ($user) {
                    if (password_verify($_POST['pwd'], $user->pwd)) {
                        $_SESSION['username'] = $user->username;
                        $_SESSION['isAdmin'] = $user->isAdmin;
                        header('Location: ./home');
                        exit();
                    } else {
                        $errors['pwd'] = 'Mot de passe incorrect.';
                    }
                } else {
                    $errors['username'] = 'Nom d\'utilisateur non trouvé.';
                }
            }
        }

        $twig = SourceTwig::getTwigEnvironment();
        // Renvoi de la vue login.html.twig avec les erreurs
        return $twig->render('login.html.twig', ['errors' => $errors, 'username' => $_POST['username'] ?? '']);
    }


    /**
     * Renvoi la vue Register
     * @return string
     */
    public function indexRegister() 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        if(isset($_POST['username']) || isset($_POST['pwd']) || isset($_POST['email'])){
            !empty($_POST['username']) ? $username = stripslashes( $_POST['username'] ) : $username = null;
            !empty($_POST['pwd']) ? $pwd = stripslashes( $_POST['pwd'] ) : $pwd = null;
            !empty($_POST['email']) ? $email = stripslashes( $_POST['email'] ) : $email = null;
            
            return $twig->render('register.html.twig', ['username' => $username, 'pwd' => $pwd, 'email' => $email]);
        }

        // Renvoi la vue register.html.twig
        return $twig->render('register.html.twig');
    }

    /**
     * Enregistre l'utilisateur
     * @return mixed
     */
    public function register(){
        $errors = [];
        $formData = []; // Tableau des données saisies par l'utilisateur

        if (empty($_POST['username'])) {
            $errors['username'] = 'Veuillez saisir un pseudo.';
        }
        if (empty($_POST['pwd'])) {
            $errors['pwd'] = 'Veuillez saisir un mot de passe.';
        }
        if (empty($_POST['email'])) {
            $errors['email'] = 'Veuillez saisir une adresse email.';
        }
        if($_POST['pwd'] !== $_POST['pwd2']){
            $errors['pwd2'] = 'Les mots de passe ne correspondent pas.';
            $formData['username'] = $_POST['username'];
            $formData['email'] = $_POST['email'];
            $formData['pwd'] = $_POST['pwd'];
        }

        if (!empty($errors)) {
            // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
            $twig = SourceTwig::getTwigEnvironment();
            return $twig->render('register.html.twig', ['errors' => $errors, 'formData' => $formData]);
        }

        $Login = new LoginModel;
        $Login->register($_POST['username'], $_POST['pwd'], $_POST['email']);
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['isAdmin'] = 0;
        header('Location: ./home');
        exit();
    }

    /**
     * Déconnecte l'utilisateur
     * @return void
     */
    public function logout(){
        session_destroy();
        header('Location: home');
        exit();
    }

}
