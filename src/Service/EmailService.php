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
        $this->mailer->Username = $this->encryption;//'snutplateform@zohomail.com'; //'postmaster@mailgun.ibonia.mg'; // Remplacez par votre nom d'utilisateur Mailgun
        $this->mailer->Password = $this->password;//'ghrtksme34tjf'; //'2a797add93d8b8add0eaec73a40c7daa'; // Remplacez par votre mot de passe Mailgun
        $this->mailer->CharSet = 'UTF-8'; // Maintenir 'UTF-8'
        //$this->mailer->SMTPAutoTLS = false;

        $this->mailer->setFrom($this->from, 'SNUT PLATEFORME');

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
    public function sendEmail($to, $subject = 'Email from snut-plaform', $template = '', $data, $options = array(), $cc = array())
    {
        $subject = mb_convert_encoding($subject, 'UTF-8');

        $this->mailer->addAddress($to);
        $this->mailer->Subject = $subject;
        $this->mailer->msgHTML($data);
        
        if (!$this->mailer->send()) {
            echo "Mailer Error: " . $this->mailer->ErrorInfo;
        } else {
            echo "Message sent!";
        }
        /*$this->message
                    ->setSubject($subject)
                    ->setFrom($from)
                    ->setTo($to)
                    ->setBody($this->templating->render(
                        $template,
                        $data
                    ),
                    'text/html');
        // Copies
        if(count($cc) > 0){
            $this->message->setCc($cc);
        }

        if(count($options) > 0){
            // Piece joints
            if(isset($options['file'])){
                if(is_array($options['file']) && sizeof($options['file'])>=1){
                    foreach ($options['file'] as $key => $value) {
                        $this->message->attach(\Swift_Attachment::fromPath($value,$options['type'][$key]));
                    }
                }else{
                    if(!is_array($options['file'])){
                        $this->message->attach(\Swift_Attachment::fromPath($options['file'],$options['type']));
                    }else{
                        foreach($options['file'] as $v){
                            $this->message->attach(\Swift_Attachment::fromPath($v,$options['type']));
                        }
                    }
                }
            }
            // Accuse de reception
            if(isset($options['readReceiptTo'])){
                $this->message->setReadReceiptTo($options['readReceiptTo']);
            }
        }*/

       //return $this->mailer->send($this->message);
    }

}

