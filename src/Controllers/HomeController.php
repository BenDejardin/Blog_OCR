<?php 

namespace Controllers;

use Source\Twig as SourceTwig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class HomeController
{
    /**
     * Renvoi la vue Home
     * @return string
     */
    public function index() 
    {
        // Appel de la fonction getTwigEnvironment() qui retourne l'environnement Twig
        $twig = SourceTwig::getTwigEnvironment();

        // Renvoi la vue home.html.twig avec le message
        return $twig->render('home.html.twig'); 
    }

    /**
     * Envoi un mail de contact
     * @return mixed
     */
    public function contact(){

        $errors = []; // Tableau des erreurs
        $formData = []; // Tableau des données saisies par l'utilisateur

        // Vérifier les champs requis et les ajouter aux tableaux correspondants
        if (isset($_POST['nom']) && !empty($_POST['nom'])) {
            $formData['nom'] = $_POST['nom'];
        } else {
            $errors['nom'] = "Le champ Nom est requis.";
        }

        if (isset($_POST['prenom']) && !empty($_POST['prenom'])) {
            $formData['prenom'] = $_POST['prenom'];
        } else {
            $errors['prenom'] = "Le champ Prénom est requis.";
        }

        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $formData['email'] = $_POST['email'];
        } else {
            $errors['email'] = "Le champ Adresse email est requis.";
        }

        if (isset($_POST['message']) && !empty($_POST['message'])) {
            $formData['message'] = $_POST['message'];
        } else {
            $errors['message'] = "Le champ Message est requis.";
        }

        // S'il y a des erreurs, renvoyer les données et les erreurs à la vue
        if (!empty($errors)) {
            $twig = SourceTwig::getTwigEnvironment();
            return $twig->render('home.html.twig', ['errors' => $errors, 'formData' => $formData]);
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