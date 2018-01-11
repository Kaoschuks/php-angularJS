<?php
include('../../engine/engine.php');
include_once(Server_Root.'engine/libs/libs.php');
include_once('IController.php');
include_once('../view/Response.php');
define('Path', Server_Root);

@ Response::cors();

Abstract Class CoreController
{
    private static $status = null, $response = null;

    private static function authUser()
    {
        
    }

    private static function getModule()
    {
        $controller = explode('/', $_GET['controller'])[0]; 
        return (file_exists($controller.'.php'))  // Checks if the module exists
                ? self::includeModule($controller.'.php', $controller)
                : Response::json(
                    404,
                    "Modules {$controller} not found" 
                );
    }

    private static function includeModule($modules = null, $controller = null)
    {
        require_once($modules);
        return $controller::processModule(@explode('/', $_GET['controller'])[1]);
    }

    public static function Output()
    {
        // check if controller is empty
        return (@ empty(explode('/', $_GET['controller'])[0]) || @ !isset($_GET['controller'])) 
            ? Response::json(
                400,
                "Bad request made to server" 
              )
            : self::getModule();
    }
}

try
{
    $result = CoreController::Output();
    //print_r(processDir(Server_Root."/CDN/uploads"));
}
catch(Exception $ex)
{
    $result = Response::json(
        500,
        "Error :".$ex->getMessage()." occured" 
    );
}
echo $result;
?>