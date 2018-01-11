<?php
include_once('../model/DBM.php');

Abstract Class Accounts implements IController
{
    use Manager;

    private static $entities = array("fname", "lname", "mname", "sex", 'status', "email", "mobile", "address", 'authid', 'access');

    static function processModule($func = null)
    {
        DBManager::$table = strtolower(__CLASS__);
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            DBManager::$key[0] = "authid";
            DBManager::$key[1] = "access";
            DBManager::$key[2] = "uname";
        }
        $func = ($func == 'Forgot-Password')
                ? "ForgotPassword"
                : $func;
        Manager::$map = self::$entities;
        return (in_array($func, get_class_methods(__CLASS__)))
                ? self::$func()
                : Manager::init();
    }

    static function Review()
    {
        DBManager::$table = DBManager::$table.strtolower("review");
        Manager::$map = array();
        return Manager::init();
    }

    static function Access()
    {
        Manager::$map = array();
        return Rbac::rbacinit();
    }

    static function Login()
    {
        Manager::$map = array("username", "password");
        return TAuth::authInit(@explode('/', $_GET['controller'])[1]);
    }

    private static function Guest()
    {
        // if($_SERVER['REQUEST_METHOD'] == 'GET')
        // {
        //     return Response::json(405, "Request method not supportted by request");
        // }
        return Response::json(200, 
                        array(
                            "token" => Token::generateToken("Guest", @ $GLOBALS['HEADERS']['X-Token']), 
                            "data" => array(
                                "username" => "John Doe",
                                "access" => "Guest",
                                "status" => "activated"
                            ),
                        )
                    );
    }

    static function Register()
    {
        return TAuth::authInit(@explode('/', $_GET['controller'])[1]);
    }
    
    static function Remove()
    {
        return TAuth::authInit(@explode('/', $_GET['controller'])[1]);
    }
    
    static function Auth()
    {
        $key = explode('/', $_REQUEST['controller']);
        $func = $key[1];
        unset($key[1]);
        $_REQUEST['controller'] = implode("/", $key);
        $_GET['controller'] = implode("/", $key);
        return TAuth::authInit($func);
    }
    
    static function ForgotPassword()
    {
        $key = explode('/', $_REQUEST['controller']);
        $func = ($key[1] == 'Forgot-Password')
                ? "ForgotPassword"
                : $key[1];
        unset($key[1]);
        $_REQUEST['controller'] = implode("/", $key);
        $_GET['controller'] = implode("/", $key);
        return TAuth::authInit($func);
    }
}

?>