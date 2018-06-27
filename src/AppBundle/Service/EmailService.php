<?php

namespace AppBundle\Service;

class EmailService
{
    public function email($to, $subject, $message)
    {
        $from       = "no-reply@miketropea.com";
        $headers    = 'MIME-Version: 1.0' . "\r\n" . 
                        'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
                        'From: '.$from."\r\n" .
                        'Reply-To: '.$from."\r\n" .
                        'X-Mailer: PHP/' . phpversion();
       if( !mail($to, $subject, $message, $headers) ) {
            return false;
       }
    }
}