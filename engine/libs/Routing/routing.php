<?php

Class Routing
{
    private static $routes = array();
    public static function checkRoute($route)
    {
        self::$routes = parse_ini_file(Server_Root.'engine/config/modules/'.$route.'/routes.conf');
        switch($route)
        {
            case "Website":
            {
                if(empty($_GET['uri']))
                {
                    $_GET['uri'] = "Home";
                }
                break;
            }
            case "Admin":
            {
                if(empty($_GET['uri']))
                {
                    header("location: Login");
                    $_GET['uri'] = "Dashboard";
                }
                break;
            }
        }
        $_GET['title'] = explode("/", $_GET['uri'])[0];
        return (in_array($_GET['title'], self::$routes)) ? "routed" : $_GET['title'];
    }
}
?>