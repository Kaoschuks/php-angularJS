<?php

Trait TMail
{
    static $mailler, $mailConfig, $dbTable;

    static function init()
    {
        self::$mailConfig = parse_ini_file(Server_Root."engine/config/external/mail.conf");
        self::$mailler = new PHPMailer();  
        self::$mailler->SMTPAuth = true;
        self::$mailler->SMTPSecure = "tls";
        self::$mailler->Host = self::$mailConfig['Host'];
        self::$mailler->Port = 465;
        self::$mailler->Username = self::$mailConfig['Username'];
        self::$mailler->Password = self::$mailConfig['Password'];
        self::$mailler->Encoding = '7bit';
    }
    
    static function Incoming()
    {
        return (self::$mailler->send())
                ? Response::json(200, 'Message has been sent')
                : Response::json(409, 'Message could not be sent because ' . self::$mailler->ErrorInfo);
    }

    static function Outgoing($data)
    {
        self::$mailler->addReplyTo($data['email']);
        self::$mailler->Subject = $_POST['subject'];
        self::$mailler->Body = $_POST['message'];
        self::$mailler->From = $data['email'];
        self::$mailler->FromName = $data['sender'];
        return (self::$mailler->send())
                ? 'Message has been sent'
                : 'Message could not be sent because ' . self::$mailler->ErrorInfo;
    }
}

?>