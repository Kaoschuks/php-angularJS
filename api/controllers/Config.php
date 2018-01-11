<?php

include_once('../model/DBM.php');

Abstract class Config
{
    private static $method;
    
    final public function processModule($func = null)
    {
        self::$method = strtolower($func);
        return (in_array($func, get_class_methods(__CLASS__)))
                ? self::$func()
                : self::all();
    }

    private function all()
    {
        $_GET['controller'] = "/Site/Website";
        self::$method = strtolower('routes');
        $routes = json_decode(self::Routes(), true)['Output'];
        self::$method = strtolower('site');
        $seo = json_decode(self::Seo(), true)['Output'];
        $_GET['controller'] = "Mail";
        self::$method = strtolower('mail');
        $mail = json_decode(self::Mail(), true)['Output'];
        return Response::json(200, array(
            'routes' => $routes,
            'seo' => $seo,
            'mail' => $mail,
        ));
    }

    private function Routes()
    {
        return self::configuration();
    }

    private function Seo()
    {
        return self::configuration();
    }

    private function Site()
    {
        return self::configuration();
    }

    private function Mail()
    {
        return self::external();
    }

    private function Facebook()
    {
        return self::external();
    }

    private function Google()
    {
        return self::external();
    }

    final private function external()
    {
        switch($_SERVER['REQUEST_METHOD'])
        {
            case "POST":
            {
                if(empty($_POST)):
                    return Response::json(400, 'Bad request with route data not sent');
                endif;
                    return (create_file(Server_Root.'engine/config/external/'.self::$method.'.conf', parse_ini_file_format($_POST)) === "File created and data written")? Response::json(200, self::$method.' data saved'): Response::json('501', self::$method.' data not saved');
                break;
            }
            case "GET":
            {
                $routes = parse_ini_file(Server_Root.'engine/config/external/'.self::$method.'.conf');
                return (!empty($routes)) ? Response::json(200, $routes): Response::json(200, 'no '.self::$method.' data found');
                break;
            }
        }
    }

    final private function configuration()
    {
        if(!isset(explode('/', $_GET['controller'])[2])):
            Response::json(400, 'Bad request with missing '.self::$method.' type');
        endif;
            switch($_SERVER['REQUEST_METHOD'])
            {
                case "POST":
                {
                    if(empty($_POST)):
                        return Response::json(400, 'Bad request with route data not sent');
                    endif;
                        return (create_file(Server_Root.'engine/config/modules/'.explode('/', $_GET['controller'])[2].'/'.self::$method.'.conf', parse_ini_file_format($_POST)) === "File created and data written")? Response::json(200, self::$method.' data saved'): Response::json('501', self::$method.' data not saved');
                    break;
                }
                case "GET":
                {
                    $routes = parse_ini_file(Server_Root.'engine/config/modules/'.explode('/', $_GET['controller'])[2].'/'.self::$method.'.conf');
                    return (!empty($routes)) ? Response::json(200, $routes): Response::json(200, 'no '.self::$method.' data found');
                    break;
                }
            }
    }

    final private function Pages()
    {
        DBManager::$table = "navigation";
        DBManager::connect();
        if(!isset(explode('/', $_GET['controller'])[2])):
            Response::json(400, 'Bad request with missing navigation type');
        endif;
            switch($_SERVER['REQUEST_METHOD'])
            {
                case "POST":
                {
                    $_POST['type'] = explode('/', $_GET['controller'])[2];
                    $_POST['pageid'] = base64_encode($_POST['name'].$_POST['type']);
                    DBManager::$key = array('pageid');
                    DBManager::$data = $_POST;
                    $resp = DBManager::Save();
                    DBManager::disconnect();
                    return ($resp !== "Inserted") ? Response::json(409, "Page navigation already exists") : Response::json(200, "Page navigation saved");
                    break;
                }
                case "PUT":
                {
                    parse_str(file_get_contents("php://input"), $GLOBALS['PUT']);
                    $GLOBALS['PUT']['type'] = explode('/', $_GET['controller'])[2];
                    DBManager::$key = array('pageid');
                    DBManager::$data = $GLOBALS['PUT'];
                    $resp = DBManager::Update();
                    return ($resp !== "Updated") ? Response::json(409, "Page navigation not updated") : Response::json(200, "Page navigation updated");
                    break;
                }
                case "DELETE":
                {
                    parse_str(file_get_contents("php://input"), $_DELETE);
                    $_DELETE['type'] = explode('/', $_GET['controller'])[2];
                    DBManager::$key = array('pageid', 'type');
                    DBManager::$data = $_DELETE;
                    $resp = DBManager::Delete();
                    return ($resp !== 'Deleted') ? Response::json(409, "Page navigation not deleted") : Response::json(200, "Page navigation deleted");
                    break;
                }
                case "GET":
                {
                    $resp;
                    switch(isset(explode('/', $_GET['controller'])[3]))
                    {
                        case true:
                        {
                            DBManager::connect();
                            DBManager::$key = array('type', 'name');
                            DBManager::$data = array(                                        
                                                'type' => explode('/', $_GET['controller'])[2], 
                                                'name' => explode('/', $_GET['controller'])[3], 
                                                );
                            $resp = DBManager::Read()[0];
                            DBManager::disconnect();
                            break;
                        }
                        default:
                        {
                            DBManager::connect();
                            DBManager::$key = array('type');
                            DBManager::$data = array( 'type' => explode('/', $_GET['controller'])[2]);
                            $resp = DBManager::Read();
                            DBManager::disconnect();
                            break;
                        }
                    }
                    return ($resp === 200) ? Response::json(200, "No Content Found") : Response::json(200, $resp) ;
                    break;
                }
            }
    }
}

?>