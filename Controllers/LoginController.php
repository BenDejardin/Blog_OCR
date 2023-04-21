<?php 

namespace Controllers;

use Models\LoginModel;
use Source\Twig as SourceTwig;

class LoginController
{
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

    public function login() 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        $Login = new LoginModel;
        $user = $Login->login($_POST['username']);
        if($user){
            if(password_verify($_POST['pwd'], $user->pwd)){
                $_SESSION['username'] = $user->username;
                $_SESSION['isAdmin'] = $user->isAdmin;
                header('Location: ./home');
                exit();
            }
        }
        // Renvoi la vue login.html.twig
        header('Location: ./login?'.$_POST['username']);
        exit();
    }

    public function indexRegister() 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        if(isset($_POST['username']) || isset($_POST['pwd']) || isset($_POST['email'])){
            !empty($_POST['username']) ? $username = $_POST['username'] : $username = null;
            !empty($_POST['pwd']) ? $pwd = $_POST['pwd'] : $pwd = null;
            !empty($_POST['email']) ? $email = $_POST['email'] : $email = null;
            
            return $twig->render('register.html.twig', ['username' => $username, 'pwd' => $pwd, 'email' => $email]);
        }

        // Renvoi la vue register.html.twig
        return $twig->render('register.html.twig');
    }

    public function register(){
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        if (empty($_POST['username']) || empty($_POST['pwd']) || empty($_POST['email'])) {
            header('Location: ./register');
            exit();
        }
        if($_POST['pwd'] !== $_POST['pwd2']){
            header('Location: ./register');
            exit();
        }
        $Login = new LoginModel;
        $Login->register($_POST['username'], $_POST['pwd'], $_POST['email']);
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['isAdmin'] = 0;
        header('Location: ./home');
        exit();
    }

    public function logout(){
        session_destroy();
        header('Location: home');
        exit();
    }

}
