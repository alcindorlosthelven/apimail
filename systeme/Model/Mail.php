<?php
/**
 * Created by PhpStorm.
 * User: ALCINDOR LOSTHELVEN
 * Date: 17/08/2018
 * Time: 11:28
 */

namespace systeme\Model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    private static $configuration;
    private static $from;

    public function __construct($configuration=array())
    {
        self::$configuration=$configuration;
        self::$from=$configuration['from'];

    }

    private function configuration(){
        //Server settings
        try{
            $mail = new PHPMailer(true);// Passing `true` enables exceptions
            //$mail->SMTPDebug = 2;
            if (strpos($_SERVER['HTTP_HOST'], "localhost") !== FALSE) {
                $mail->isSMTP();
            }
            $mail->Host =self::$configuration['host'];  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = self::$configuration['utilisateur'];                 // SMTP username
            $mail->Password = self::$configuration['motdepasse'];                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port =self::$configuration['port'];
            // TCP port to connect to
            return $mail;
        }catch (Exception $ex){
            throw new Exception($ex->getMessage());
        }
    }

    public function envoyer($to,$sujet,$contenue,$atachement="",$reply=""){

        try {
            $mail=$this->configuration();
            $mail->setFrom(self::$from['email'],self::$from['nom']);

            if(is_array($to)){
                foreach ($to as $m){
                    if (strpos($m, ",") !== FALSE) {
                        $t=explode(",",$m);
                        $mail->addAddress($t[0],$t[1]);
                    }else{
                        $mail->addAddress($m);
                    }
                }
            }else{
                if (strpos($to, ",") !== FALSE) {
                    $t=explode(",",$to);
                    $mail->addAddress($t[0],$t[1]);
                }else{
                    $mail->addAddress($to);
                }
            }

            if($atachement != "") {
                if (is_array($atachement)) {
                    foreach ($atachement as $attache) {
                        $mail->addAttachment($attache);
                    }
                } else {
                    $mail->addAttachment($atachement);
                }
            }

            if($reply != ""){
                $mail->addReplyTo($reply);
            }

           /*
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');*/

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $sujet;
            $mail->Body    = $contenue;

            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return "ok";
        } catch (Exception $e) {
            return 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
        }
    }


}