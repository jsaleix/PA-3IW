<?php

namespace App\Models;

use App\Core\Database;
use PHPMailer\PHPMailer\PHPMailer;
require_once __DIR__ . '/../vendor/autoload.php';

class Mail extends Database
{

    public function createTransporter(){
        $mailing = new PHPMailer(True);
        $mailing->isSMTP();
        $mailing->Host = MAIL_SMTP;
        $mailing->SMTPAuth = true;
        $mailing->Username = MAIL;
        $mailing->Password = MAIL_PWD;
        $mailing->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mailing->Port = 465;
        return $mailing;
    }

    public function sendMail(array $mail): bool
    {
        [ 'from' => $mailFrom, 'to' => $mailTo, 'subject' => $subject, 'body' => $body ] = $mail;
        if(empty($mailFrom) || empty($mailTo) || empty($subject) || empty($body)){
            return false;
        }
        $mailing = $this->createTransporter();
        $mailing->setFrom(MAIL, $mailFrom);
        $mailing->addAddress($mailTo);
        $mailing->isHTML(true);
        $mailing->Subject = $subject;
        $mailing->Body = $body;
		if(!$mailing->send()){
            echo "aqhuiaqhuiaze";
            return false;
        }
        echo "keazozae";
        return true;
    }

}