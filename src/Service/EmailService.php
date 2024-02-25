<?php
namespace App\Service;

class EmailService
{
    protected $mailer;
    protected $host = 'localhost';//mail.nogevents.com //localhost configuration local
    protected $smtpPort = '1025';//465 // configuration local
    protected $encryption = null;//ssl // configuration local
    protected $templating;
    protected $transport;
    protected $message;


    /**
     * init service
     *
     * @param object $service
     * @return $this
     */
    public function __construct(\Twig\Environment $templating) {

        $this->transport = new \Swift_SmtpTransport($this->host, $this->smtpPort, $this->encryption);
        //$this->transport->setUsername('matac@nogevents.com');   // Supprimer si c'est local
        //$this->transport->setPassword('Ups@.]h.dwFk'); // Supprimer si c'est local
        $this->mailer = new \Swift_Mailer($this->transport);;
        $this->templating = $templating;
        $this->message = new \Swift_Message();
        /**
         * Eviter erreur socket_client   // Supprimer si c'est local
         */
        /*$options = array(
            "ssl" => array(
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ),
        );
        $this->transport->setStreamOptions($options);*/
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
    public function sendEmail($from, $to, $subject = 'Email from oatf-matac', $template = '', $data = [], $options = array(), $cc = array()):bool
    {
        $this->message
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
        }

       return $this->mailer->send($this->message);
    }

}

