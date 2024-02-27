<?php

// src/Controller/MailerController.php
namespace App\Controller;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
#[Route('/mailer', name: 'app_mailer')]
    public function sendEmail(MailerInterface $mailer)
    {

        $smtpHost = 'snut-plateform.mg';
        $smtpPort = 465; // Utilisez le port SMTP approprié
        $smtpUsername = 'louis_fernand';
        $smtpPassword = 'JTy3w9x3q2%';

        $token = "XYZ";

        $subject = "SNUT-PLATEFORME : REINITIALISATION DE MOT DE PASSE";
        $subject = mb_convert_encoding($subject, 'UTF-8');
        $urlReset = $this->generateUrl('app_reset_password', ['token' => $token]);
        $htmlContent = '
            Bonjour <b> RASOA VAO </b>,<br/><br/>
            Pour réinitialiser votre mot de passe dans la plateforme, veuillez cliquer sur le lien ci-dessous : <br/>
            <a href="' . $urlReset . '">Réinitialiser mon mot de passe</a> <br/>
            Ce lien expirera le <b> demain</b>. <br/> <br/>
            Cordialement, <br/>
            Ceci est un mail automatique, ne pas répondre'; 
        $mail = new PHPMailer();

        try {
			$mail->SMTPDebug = 2;
/*
$config["smtp_host"] = "51.254.136.46";
$config["smtp_port"] = 587;
$config["smtp_secure"] = "tls";
$config["smtp_ignore_server_certificate"] = true;
$config["smtp_auth"] = true;
$config["smtp_username"] = "smtp.vps4@ibonia.mg";
$config["smtp_password"] = "m-smtp2020"; // "lnam9XEG@M";
$config["smtp_charset"] = "UTF-8";
$config["smtp_encoding"] = "base64";

$config["smtp_host"] = "smtp.mailgun.org";
$config["smtp_port"] = "465";               // 465 for "ssl" and 587 for "tls".
$config["smtp_secure"] = "ssl";             // "ssl" or "tls"
$config["smtp_ignore_server_certificate"] = false;
$config["smtp_auth"] = true;
$config["smtp_username"] = "postmaster@mailgun.ibonia.mg";
$config["smtp_password"] = "2a797add93d8b8add0eaec73a40c7daa";
$config["smtp_charset"] = "UTF-8";
$config["smtp_encoding"] = "base64";



*/

           $mail->isSMTP(); 
           $mail->Host = 'smtp.zoho.com'; //"smtp.zoho.com"; //"smtp.mailgun.org";
           $mail->Port = 465;
           $mail->SMTPSecure = 'ssl';
           $mail->SMTPAuth = true;
           $mail->Username = 'snutplateform@zohomail.com';//'snutplateform@zohomail.com'; //'postmaster@mailgun.ibonia.mg'; // Remplacez par votre nom d'utilisateur Mailgun
           $mail->Password = 'ghrtksme34tjf';//'ghrtksme34tjf'; //'2a797add93d8b8add0eaec73a40c7daa'; // Remplacez par votre mot de passe Mailgun
           $mail->CharSet = 'UTF-8'; // Maintenir 'UTF-8'
           //$mail->SMTPAutoTLS = false;

            $mail->setFrom("snutplateform@zohomail.com", 'RLFThierry');
            $mail->addAddress("nnavalona@gmail.com");
            $mail->Subject = $subject;
            $mail->msgHTML($htmlContent);
            
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                echo "Message sent!";
            }

            dd($mail);
        } catch (Exception $e) { 
            // Gestion des erreurs d'envoi d'e-mail
            $errorMessage = 'Erreur lors de l\'envoi de l\'e-mail : ' . $mail->ErrorInfo;
            // Traitez l'erreur selon vos besoins, par exemple, en ajoutant un message flash
            $this->addFlash('error', $errorMessage);
            // Redirection vers une page d'erreur ou autre action en cas d'échec d'envoi
           dd($e);
        }
    }
}
