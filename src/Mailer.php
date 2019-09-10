<?php
/**
 * This is the mailer class, mainly used for sending
 * confirmation emails.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class Mailer
 */
class Mailer {

    private $mailer;
    private $mail_to;

    public function __construct() {
        $this->mailer = new PHPMailer;
        $this->mailer->SMTPDebug = 2;
        $this->mailer->isSMTP();
        $this->mailer->SMTPAuth = true;
        $this->mailer->Host = getenv('SMTP_HOST');
        $this->mailer->Username = getenv('SMTP_UNAME');
        $this->mailer->Password = getenv('SMTP_PASS');
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->Port = getenv('SMTP_PORT');
        try {
            $this->mailer->setFrom("mackilanu@gmail.com");
        }catch (Exception $e) {
            echo $e;
            return;
        }
        $this->mailer->isHTML(true);

        $this->mailer->Subject = 'Verify your ' . getenv('APP_NAME') . ' account';

    }

    /**
     * @param $email
     * Set the email to send email to.
     */
    public function set_to($email) {
        $this->mailer->addAddress($email);
    }
    public function send_verification_mail(string $verification_token)
    {
        $app_name = getenv('APP_NAME');
        $this->mailer->Body = <<<BODY
        Thank you for signing up on {$app_name}!
        
        Plesae follow the link below to verify your email
        
        https://autho.com/verify/{$verification_token}
BODY;
        try {
            $this->mailer->send();
        } catch (Exception $e) {
            echo $e;
            return;
        }
    }
}