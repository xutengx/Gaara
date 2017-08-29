<?php
namespace App\Dev\mail;

class index{
    public function send(\Mail $mail, $emailAddr){
        $mail->Subject = 'Subject';
        $mail->Body = 'test email';
        $mail->AddAddress($emailAddr);
        $mail->send();
    }
}