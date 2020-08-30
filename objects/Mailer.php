<?php

// 'Email' Object
class Mailer {
    
    // database connection and table name
    private $mailer;
    private $email_max = "a.makro@web.de";

    // object properties
    public $content;

    // constructor
    public function __construct($phpmail){
        $this->mailer = $phpmail;
    }

    // send Mail Functions
    public function sendSubmitMail() {

        try {

            //Recipients
            $this->mailer->setFrom('noreply@mks-software.de', 'noreply@mks-software.de');
            $this->mailer->addAddress($this->email_max);

            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->Encoding = 'base64';

            //Content
            $this->mailer->isHTML(true);                                    // Set email format to HTML
            $this->mailer->Subject = 'PS - Neuer Event Vorschlag';
            $this->mailer->Body    = $this->content;
            $this->mailer->AltBody = strip_tags($this->content);

            $this->mailer->send();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}

?>