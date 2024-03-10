<?php
namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    protected $mailer;
    protected $host = 'smtp.zoho.com';//mail.nogevents.com //localhost configuration local
    protected $smtpPort = 465;//465 // configuration local
    protected $encryption = 'ssl';//ssl // configuration local
    protected $username = "snutplateform@zohomail.com";
    protected $password = "ghrtksme34tjf";
    protected $from = "snutplateform@zohomail.com";
    protected $templating;
    protected $transport;
    protected $message;


    /**
     * init service
     *
     * @param object $service
     * @return $this
     */
    public function __construct() {

        $this->mailer = new PHPMailer();
   
        $this->mailer->isSMTP(); 
        $this->mailer->Host = $this->host; //"smtp.zoho.com"; //"smtp.mailgun.org";
        $this->mailer->Port = $this->smtpPort;
        $this->mailer->SMTPSecure = $this->encryption;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->username;//'snutplateform@zohomail.com'; //'postmaster@mailgun.ibonia.mg'; // Remplacez par votre nom d'utilisateur Mailgun
        $this->mailer->Password = $this->password;//'ghrtksme34tjf'; //'2a797add93d8b8add0eaec73a40c7daa'; // Remplacez par votre mot de passe Mailgun
        $this->mailer->CharSet = 'UTF-8'; // Maintenir 'UTF-8'
        //$this->mailer->SMTPAutoTLS = false;
        $this->mailer->SMTPOptions = [
            'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            ]
            ];
        $this->mailer->SMTPDebug = 3;
        return $this;
    }

    /**
     * Send email
     *
     * @param string $from
     * @param string $to
     * @param twig $view
     * @param string $subject
     */
    public function sendEmail($to , $subject = 'Email from snut-plaform', $data = null, $options = array(), $cc = array())
    {
        $subject = mb_convert_encoding($subject, 'UTF-8');
        $this->mailer->setFrom($this->from, 'SNUT PLATEFORME');
        $this->mailer->addAddress($to);
        
        /*foreach ($tos as $to => $nom) {
            $this->mailer->addAddress($to, $nom);
        }*/
        
        $this->mailer->Subject = $subject;
        $this->mailer->msgHTML($data);
        if (!$this->mailer->send()) {
            dd($this->mailer->ErrorInfo);
            echo "Mailer Error: " . $this->mailer->ErrorInfo;
        } else {
            echo "Message sent!";
        }
    }

}

