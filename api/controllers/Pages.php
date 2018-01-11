<?php

include_once('../model/DBM.php');

Abstract class Pages 
{
    private static $interface, $path;

    public function processModule($func = null)
    {
        self::$path = Server_Root.'engine/interfaces/';
        self::$interface = strtolower($func);
        return (in_array($func, get_class_methods(__CLASS__)))
                ? self::$func()
                : Response::json(400, 'Bad Request made to server');
    }

    private function Faq()
    {
        DBManager::connect();
        DBManager::$table = 'faqs';
        switch($_SERVER['REQUEST_METHOD'])
        {
            case "POST":
            {
                DBManager::$key = array('question');
                DBManager::$data = $_POST;
                $resp = DBManager::Save();
                return ($resp !== 'Inserted') ? Response::json(409, "Faq already exists") : Response::json(200, "Faq saved");
                break;
            }
            case "PUT":
            {
                parse_str(file_get_contents("php://input"), $GLOBALS['PUT']);
                DBManager::$key = array('id');
                DBManager::$data = $GLOBALS['PUT'];
                $resp = @ DBManager::Update();
                DBManager::disconnect();
                return ($resp !== 'Updated') ? Response::json(409, "Faq not updated") : Response::json(200, "Faq updated");
                break;
            }
            case "DELETE":
            {
                DBManager::$key = array(explode('/', $_GET['controller'])[2]);
                DBManager::$data = array(explode('/', $_GET['controller'])[2] => explode('/', $_GET['controller'])[3]);
                $resp = DBManager::Delete();
                DBManager::disconnect();
                return ($resp !== 'Deleted') ? Response::json(409, "Faq not deleted") : Response::json(200, "Faq deleted");
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

    private function About()
    {
        return self::pagedata();
    }

    private function Copyrights()
    {
        return self::pagedata();
    }

    private function Terms()
    {
        return self::pagedata();
    }

    private function Privacy()
    {
        return self::pagedata();
    }

    private function pagedata()
    {
        switch($_SERVER['REQUEST_METHOD'])
        {
            case "POST":
            {
                $resp = (empty($_POST) || empty($_POST['data']) || !isset($_POST['data'])) ? "Error page data missing" : create_file(self::$path.self::$interface.".thtml", $_POST['data']);
                return ($resp === 'File created and data written') ? Response::json(200, 'Page data saved'): Response::json(409, $resp);
                break;
            }
            case "GET":
            {
                $resp = read_file(self::$path.self::$interface.".thtml");
                return ($resp === 'File opened but no data was read') ? Response::json(409, $resp): Response::json(200, $resp);
                break;
            }
        }
    }
}

?>