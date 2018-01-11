<?php
include_once('../model/DBM.php');

Abstract Class Users implements IController
{
    use Manager;

    private static $entities = array("uname", "fname", "lname", "mname", "sex", "email", "mobile", "address", 'authid', 'access');

    static function processModule($func = null)
    {
        DBManager::$table = strtolower(__CLASS__);
        DBManager::$key[0] = "authid";
        DBManager::$key[1] = "access";
        DBManager::$key[2] = "uname";
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

    static function Guest()
    {
        return TAuth::authInit(@explode('/', $_GET['controller'])[1]);
    }

    static function Register()
    {
        return TAuth::authInit(@explode('/', $_GET['controller'])[1]);
    }
    
    static function Remove()
    {
        return TAuth::authInit(@explode('/', $_GET['controller'])[1]);
    }
}

?>