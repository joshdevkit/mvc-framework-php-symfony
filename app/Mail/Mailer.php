<?php

namespace App\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    protected PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->initializeMailer();
    }

    protected function initializeMailer()
    {
        $this->mailer->isSMTP();
        $this->mailer->Host       = $_ENV['MAIL_HOST'];
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $_ENV['MAIL_USERNAME'];
        $this->mailer->Password   = $_ENV['MAIL_PASSWORD'];
        $this->mailer->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
        $this->mailer->Port       = $_ENV['MAIL_PORT'];

        $this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $this->mailer->isHTML(true);
    }

    public function send(string $recipientEmail, string $recipientName, string $subject, string $body): bool
    {
        try {
            $this->mailer->addAddress($recipientEmail, $recipientName);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            echo "Mailer Error: {$this->mailer->ErrorInfo}";
            return false;
        }
    }
}
