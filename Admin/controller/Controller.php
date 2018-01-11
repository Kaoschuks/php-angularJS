<?php

include('../../engine/engine.php');
includeFile('engine/libs/libs.php');
const Route = "Admin";
$config = parse_ini_file(Server_Root."engine/config/modules/".Route."/site.conf");

Class Controller extends BasicSecurity
{
    private static $route, $page, $pageData,
            $error_pages = array("400", "401", "402", "403", "404", "500", "501", "503"),
            $error_message = array(
                "400" => "Bad Request", 
                "401" => "Unauthorised User", 
                "403" => "Access Forbidden", 
                "404" => "Page Not Found", 
                "500" => "Internal Server Error", 
                "501" => "Service Functionality Not Implemented", 
                "503" => "Services unavailable"
            );
	
	public static function invoke()
	{
        self::$route = Routing::checkRoute(Route);
        return (string)self::Routing();
	} 
    
    private function generateUI()
    {
        // if($GLOBALS['config']['Minify'])
        // {
        //     minFile($arr, $type, $file);
        // }

        ob_start();
        print eval('?>'.file_get_contents('../view/Traits/Header.php', TRUE));
        $header = ob_get_contents();
        ob_end_clean();
        
        ob_start();
        print eval('?>'.file_get_contents('../view/Traits/Nav.php', TRUE));
        $nav = ob_get_contents();
        ob_end_clean();              
        
        ob_start();
        print eval('?>'.file_get_contents('../view/Traits/Footer.php', TRUE));
        $footer = ob_get_contents();
        ob_end_clean();
        
        return (string)$header.$nav.self::$page.$footer;
    }
    
    private function Routing()
    {
        switch(self::$route)
        {
            case "routed":
            {
                $name = $_GET['title'];
                //self::$pageData = PHPWee\Minify::html(self::generateUI($name));
                self::$pageData = self::generateUI();
                header("HTTP/1.1 200 $name");
                break;
            }
            case "400" :
            case "401" :
            case "403" :
            case "404" :
            case "500" :
            case "501" :
            case "503" :
            {
                $error_message = self::$error_message[self::$route];
                $route = self::$route;
                ob_start();
                print eval('?>'.file_get_contents('../view/Pages/Error.php', TRUE));
                self::$pageData = ob_get_contents();
                ob_end_clean();
                header("HTTP/1.1 $route $error_message");
                break;
            }
            default:
            {
                $err = "404";
                header("location: $err");
            }
        }
        return (string)self::$pageData;
    }
}

try 
{
    $_REQUEST = BasicSecurity::securityLogic($_REQUEST);
    $result = Controller::invoke();
    echo $result;
} 
catch( Exception $e ) 
{  
    header("location:500");
}
exit();

?>