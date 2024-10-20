<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{

    public $adminEmail = null;
    public $programmerEmail = null;
    public $headers = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        // it does not work
    }

    /**
     *
     * @param string $to
     * @param string $subject
     * @param var $body
     */
    public function send($to, $subject, $body)
    {
//Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host = 'abidata.co';                     //Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication
            $mail->Username = 'noreplay@abidata.co';                     //SMTP username
            $mail->Password = 'XQq,3aJcC8BS';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('noreplay@abidata.co', 'Khoshobesh');
            $mail->addAddress($to, 'Joe User');     //Add a recipient
            //  $mail->addAddress('ellen@example.com');               //Name is optional
            //  $mail->addReplyTo('info@example.com', 'Information');
            //  $mail->addCC('cc@example.com');
            //  $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            // Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body ='<meta charset="utf-8"><body>' . $body. '</body>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }

}
