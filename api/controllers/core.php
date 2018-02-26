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
        // DataSecurity::$headers['X-TOKEN'];
        if($_SERVER['REQUEST_METHOD'] === "GET" && $_REQUEST['controller'] == 'Accounts/Guest')
        {
            return "authroised";
        }
        else{
            return "authroise";
        }
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
        // $auth = self::authUser();
        // if($auth !== "authorised")
        // {
        //     return Response::json(
        //         403,
        //         "Access denied to server. Authorization required" 
        //     );
        // }
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
    DataSecurity::init();
    // print_r(DataSecurity::$headers);
    $result = CoreController::Output();
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