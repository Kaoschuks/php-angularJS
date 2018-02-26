<?php

Trait Response
{
    private static $statuscodes = status;

    public static function json($status = 404, $data = array())
    {
        header("content-type:application/json");
        @ header("HTTP/1.1 ".(int)$status." ".self::$statuscodes[$status]);
        // if($_GET['controller'] !== "Analytics/Logs" || $_GET['controller'] !== "Accounts/Guest" || $_GET['controller'] !== 'Analytics/Visitor/View')
        // {
        //     self::recordRequest(
        //         $status, 
        //         self::$statuscodes[$status], 
        //         (is_string($data) || $_SERVER['REQUEST_METHOD'] !== 'GET') ? $data : "Request successful"
        //     );
        // }
        if(is_array($data)){$data['count'] = count($data);}
        return json_encode(
            array(
                "Status" => (int)$status,
                "Message" => (string)self::$statuscodes[$status],
                //"Output" => DataSecurity::secureData('Encode', $data)
                "Output" => $data
            ),
            JSON_PRETTY_PRINT
        );
    }

    public static function cors()
    {
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
        if(in_array($_SERVER['REQUEST_METHOD'], array('POST', 'GET', 'DELETE', 'PUT')))
        {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        }
        else if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') 
        {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            exit(0);
        }
    }

    private static function recordRequest($status = null, $msg = null, $data = null)
    {
        DBManager::connect();
        DBManager::$table = 'servicelogs';
        // $date = date("d:m:y");
        $time = date("G:i:s", time());
        DBManager::$data = array(
            "status" => $status,
            "method" => $_SERVER['REQUEST_METHOD'],
            "url" => $_GET['controller'],
            "response" => $data,
            'time' => $time,
            'year' => date("y"),
            'month' => date("m"),
            'day' => date("d"),
        );
        DBManager::Add();
        DBManager::disconnect();
        // update_file(
        //     Path.getenv('SERVICE_LOG'),
        //     $resp
        // );     
    }
}


?>