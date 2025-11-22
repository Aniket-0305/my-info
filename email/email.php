<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions


function sendmail($to,$cc,$bcc,$subject,$message){

    try {
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();        
        //$mail->Host       = 'mail.itologyinventor.com';                    //Set the SMTP server to send through
        $mail->Host       = 'mail.itology.in'; 
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        //$mail->Username   = 'feedback_@itologyinventor.com';                     //SMTP username
        $mail->Username   = 'feedback@itology.in';
        //$mail->Password   = 'Itology@123';                             //SMTP password
        $mail->Password   = 'ITology@123vps'; 
        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        $mail->Port       = 587;                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('feedback@itology.in', 'Aniket Kheratkar');
        $mail->addAddress($to, 'Aniket Kheratkar');     //Add a recipient
        if(!empty($cc)){
         $mail->addCC($cc);    
        }
        $mail->addBCC($bcc);
    
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
    
    
        $mail->send();
        return true;
    } catch (Exception $e) {
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    	return false;
    }
}
?>