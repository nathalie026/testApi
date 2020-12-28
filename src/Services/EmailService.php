<?php

namespace App\Services;

use Swift_Mailer;
use Swift_Message;

class EmailService
{

    public function sendMail(string $message, string $email, Swift_Mailer $mailer)
    {
        $myEmail = (new Swift_Message('Petite alerte pour ta todolist'))
            ->setFrom('todo@lol.fr')
            ->setTo($email)
            ->setBody($message);

        $mailer->send($myEmail);
    }
}