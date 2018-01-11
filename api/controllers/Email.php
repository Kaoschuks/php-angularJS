<?php

include_once('../model/DBM.php');
include_once('../model/Mailing.php');

Abstract class Email implements IController
{
    private static $response, $resp = array(), $data;

    static function processModule($func = null)
    {
        TMail::init();
        DBManager::$table = strtolower("Subscribers");
        return (in_array($func, get_class_methods(__CLASS__)))
                ? self::$func()
                : Response::json(400, "Bad request parameter not found");
    }
    
    private function Contact()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
            return Response::json(405, 'Request Method not allowed for function');
        TMail::$mailler->From = $_POST['email'];
        TMail::$mailler->FromName = $_POST['sender'];
        TMail::$mailler->addReplyTo($_POST['email']);
        TMail::$mailler->addAddress(TMail::$mailConfig['From']);
        TMail::$mailler->Subject = $_POST['subject'];
        TMail::$mailler->Body = $_POST['message'];
        return TMail::Incoming();
    }
    
    private function Subscribers()
    {
        DBManager::connect();
        switch($_SERVER['REQUEST_METHOD'])
        {
            case "POST":
            {
                $_POST['date'] = date('Y-m-d H:m:s');
                DBManager::$key[0] = 'mail';
                DBManager::$data = $_POST;
                $resp = DBManager::Save();
                DBManager::disconnect();
                return ($resp === "Not Inserted") ? Response::json(409, "Subscriber already exists") : Response::json(200, "Subscriber info saved");
                break;
            }
            case "DELETE":
            {
                if(isset(explode('/', $_GET['controller'])[2])):
                    DBManager::$key = 'mail';
                    DBManager::$data = array(
                        DBManager::$key => explode('/', $_GET['controller'])[2]
                    );
                    $_SERVER['REQUEST_METHOD'] = "POST";
                    $resp = DBManager::Delete();
                    DBManager::disconnect();
                    return Response::json(200, $resp);
                endif;
                    return Response::json(400, 'Bad request subscriber detail missing');
                break;
            }
            case "GET":
            {
                $resp = DBManager::Read();
                DBManager::disconnect();
                return ($resp === 200) ? Response::json(200, "No Content Found") : Response::json(200, $resp) ;
                break;
            }
        }
    }

    private function sendNewsletter()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        DBManager::connect();
        self::$response = DBManager::Read();
        self::$data['message'] = $_POST['message'];
        self::$data['subject'] = $_POST['subject'];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        foreach(self::$response as $key => $subscribers)
        {
            TMail::$mailler->addAddress(TMail::$mailConfig['From']);
            self::$data['email'] = $subscribers['mail'];
            self::$data['sender'] = $subscribers['name'];
            self::$resp[$subscribers['name']] = str_replace("Message", "Newsletter", TMail::Outgoing(self::$data));
        }
        DBManager::disconnect();
        return Response::json(200, self::$resp);
    }

    private function Newsletter()
    {

        switch(explode('/', $_GET['controller'])[2])
        {
            case "Messages":
            {
                DBManager::$table = strtolower("newslettermsgs");
                $_POST['created'] = Date('Y:m:d');
                Manager::$map = array('title', 'subject', 'message', 'created', 'by');
                DBManager::$key[0] = ($_SERVER['REQUEST_METHOD']) ? 'id' : 'title';
                $key = explode('/', $_REQUEST['controller']);
                unset($key[1]);
                unset($key[2]);
                $_REQUEST['controller'] = implode("/", $key);
                return Manager::init();
                break;
            }
            case "":
            {
                return self::sendNewsletter();
                break;
            }
            default:
            {
                return Response::json(400, "Bad newsletter request made to server");
                break;
            }
        }
    }

}

?>