<?php 

namespace Controllers;

use Source\Twig as SourceTwig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class HomeController
{
    // Test ou message = ....?message et peut etre null
    public function index($message = null) 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        // Pour test : Si $message est null alors $message = 'Home page'
        if ($message === null) {
            $message = 'Home page';
        }

        // Renvoi la vue home.html.twig avec le message
        return $twig->render('home.html.twig', ['name' => $message]); 
    }

    public function contact(){

        if(!isset($_POST['nom']) || empty($_POST['nom']) || !isset($_POST['prenom']) || empty($_POST['prenom']) || !isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['message']) || empty($_POST['message'])){
            header('Location: ./home');
            exit();
        }

        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bendejardintest@gmail.com';
            $mail->Password   = 'gvqohmmzasvktzek';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->setFrom($email, 'Contact');
            $mail->addAddress('bendejardintest@gmail.com', 'BenDejardin');
            $mail->isHTML(true);
            $mail->Subject = 'Demande de contact';
            $mail->Body    = 'Nom : '.$nom.' '.$prenom.'<br>'.nl2br($message);
            $mail->AltBody = nl2br($message);
            $mail->send();
            header('Location: ./home');
            exit();
        } catch (Exception $e) {
            echo "Le message n'a pas pu être envoyé. Erreur de l'expéditeur : " . htmlspecialchars($mail->ErrorInfo);
        }

    }
}