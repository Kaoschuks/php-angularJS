<?php

include_once('../model/DBM.php');

Abstract class Analytics extends shareCount
{
    private static  $url,
                    $permissionLevel = array(
                        "Social" => PERMISSION_READ,
                        "Rank" => PERMISSION_READ,
                        "Server" => PERMISSION_READ,
                        "Getvisitor" => PERMISSION_READ,
                        "Getpageview" => PERMISSION_READ,
                        "Savevisitor" => PERMISSION_ADD,
                        "Savepageview" => PERMISSION_ADD,
                    ),
                    $requestLevel = array(
                        "Social" => "GET",
                        "Rank" => "GET",
                        "Server" => "GET",
                        "Getvisitor" => 'GET',
                        "Getpageview" => 'GET',
                        "Savevisitor" => 'POST',
                        "Savepageview" => 'POST',
                    );

    public function processModule($func = null)
    {
        $_GET['url'] = parse_ini_file(Server_Root.'engine/config/modules/Website/site.conf')['SITE'];
        self::$url = 'https://jumia.com';
        // if(!General::checkRequestMethodLevel(self::$requestLevel[$func]) /*|| General::checkAccessLevel($func, $access = 'Guest', self::$permissionLevel[$func])*/)
        // {
        //     return Response::json(405, "Requested Method invalid");
        // }
        return (in_array($func, get_class_methods(__CLASS__)))
                ? self::$func()
                : Response::json(400, "Bad request parameter not found");
    }

    private function Social()
    {
        parent::init(self::$url);
        return Response::json(200, array(
            'Facebook' => array(
                'share' => parent::get_fb(),
                'likes' => '',
            ),
            'Google' => array(
                'share' => parent::get_plusones(),
                'likes' => '',
            ),
            'Twitter' => array(
                'share' => parent::get_tweets(),
                'likes' => '',
            ),
        ));
    }

    private function Rank()
    {
        return Response::json(200, array(
            "Alexa" => parent::alexaCheck(self::$url),
            'Google' => array(
                'backlink' => parent::googleBacklink(self::$url),
                'rank' => parent::getGooglePagerank(self::$url),
                'index' => parent::googleIndexPages(self::$url)
            ),
        ));
    }

    private function Server()
    {
        $prevVal = shell_exec("cat /proc/stat");
        $prevArr = explode(' ',trim($prevVal));
        $prevTotal = @ $prevArr[2] + @ $prevArr[3] + @ $prevArr[4] + @ $prevArr[5];
        $prevIdle = $prevArr[5];
        usleep(0.15 * 1000000);
        $val = shell_exec("cat /proc/stat");
        $arr = explode(' ', trim($val));
        $total = @ $arr[2] + @ $arr[3] + @ $arr[4] + @ $arr[5];
        $idle = $arr[5];
        $intervalTotal = intval($total - $prevTotal);
        $stat['cpu'] =  @ intval(100 * (($intervalTotal - ($idle - $prevIdle)) / $intervalTotal));
        $cpu_result = shell_exec("cat /proc/cpuinfo | grep model\ name");
        $stat['cpu_model'] = strstr($cpu_result, "\n", true);
        $stat['cpu_model'] = str_replace("model name    : ", "", $stat['cpu_model']);
        //memory stat
        $stat['mem_percent'] = round(shell_exec("free | grep Mem | awk '{print $3/$2 * 100.0}'"), 2);
        $mem_result = shell_exec("cat /proc/meminfo | grep MemTotal");
        $stat['mem_total'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
        $mem_result = shell_exec("cat /proc/meminfo | grep MemFree");
        $stat['mem_free'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
        $stat['mem_used'] = $stat['mem_total'] - $stat['mem_free'];
        //hdd stat
        $stat['hdd_free'] = round(disk_free_space("/") / 1024 / 1024 / 1024, 2);
        $stat['hdd_total'] = round(disk_total_space("/") / 1024 / 1024/ 1024, 2);
        $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
        $stat['hdd_percent'] = round(sprintf('%.2f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 2);
        //network stat
        $stat['network_rx'] = @ round(trim(file_get_contents("/sys/class/net/eth0/statistics/rx_bytes")) / 1024/ 1024/ 1024, 2);
        $stat['network_tx'] = @ round(trim(file_get_contents("/sys/class/net/eth0/statistics/tx_bytes")) / 1024/ 1024/ 1024, 2);
        return Response::json(
            200,     
            array(
                "cpu" => $stat['cpu'],
                "cpu_model" => $stat['cpu_model'],
                "mem_percent" => $stat['mem_percent'],
                "mem_total" => $stat['mem_total'],
                "mem_used" => $stat['mem_used'], 
                "mem_free" => $stat['mem_free'],
                "hdd_free" => $stat['hdd_free'], 
                "hdd_total" => $stat['hdd_total'], 
                "hdd_used" => $stat['hdd_used'],
                "hdd_percent" => $stat['hdd_percent'],
                "network_rx" => $stat['network_rx'],
                "network_tx" => $stat['network_tx'],
                "serversoftware" => $_SERVER['SERVER_SOFTWARE'],
                "servername" => $_SERVER['SERVER_NAME'],
            )
        );
    }

    private function pageview()
    {
        switch($_SERVER['REQUEST_METHOD'])
        {
            case "POST":
            {
                if(!isset($_POST['page']))
                {
                    return Response::json(400, "Missing parameter in request");
                }
                $_POST['ip'] = $_SERVER['REMOTE_ADDR'];
                $_POST['date'] = date("Y-m-d") ;
                $_POST['time'] = date("H:i:s") ;
                $_POST['isbot'] = Tracking::isBot() ? '1' : '0' ;
                DBManager::$data = $_POST;
                $data = DBManager::Add();
                return ($data === 'Inserted')?Response::json(200, array("Page view data saved")):Response::json(409, 'Request not processed');
                break;
            }
            case "GET":
            {
                self::$url = explode('/', $_GET['controller']);
                switch(@ strtolower(self::$url[3]))
                {
                    case 'date':
                    case 'browser':
                    case 'ip':
                    {
                        DBManager::$key[0] = strtolower(self::$url[3]);
                        DBManager::$data = array(strtolower(self::$url[3]) => strtolower(self::$url[4]));
                        $data = DBManager::Read();
                        return (empty(self::$url[3]))?Response::json(400, strtoupper(self::$url[2])." missing in request"):Response::json(200, $data);
                        break;
                    }
                    default:
                    {
                        $data = DBManager::Read();
                        return ($data === "Error occured")?Response::json(400, strtoupper(self::$url[3])." missing in request"):Response::json(200, $data);
                        break;
                    }
                }
                break;
            }
        }
    }

    private function Visitor()
    {
        switch(@ explode("/", $_GET['controller'])[2])
        {
            case "History":
            {
                return self::History();
                break;
            }
            case "View":
            {
                DBManager::connect();
                DBManager::$table = strtolower('pageview');
                DBManager::disconnect();
                return self::pageview();
                break;
            }
            case 'Date':
            case 'Browser':
            case 'IP':
            {
                DBManager::$table = strtolower('visitor');
                self::$url = explode('/', $_GET['controller']);
                DBManager::$key[0] = self::$url[2];
                DBManager::$data = array(self::$url[2] => self::$url[3]);
                DBManager::connect();
                $data = DBManager::Read();
                DBManager::disconnect();
                return (empty(self::$url[3]) || $data === "Error occured")?Response::json(400, strtoupper(self::$url[2])." missing in request"):Response::json(200, $data);
                break;
            }
            default:
            {
                DBManager::$table = strtolower('visitor');
                if($_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    $api= "1ade0eec6de005cfeedd12678aac3cbf4f47c120bbf83b3cc" ;
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $apiurl = "http://api.ipinfodb.com/v3/ip-city/?key=$api&ip=$ip" ;
                    DBManager::connect();
                    DBManager::$data = Tracking::trackInfo($apiurl);
                    DBManager::$key[0] = 'ip';
                    $data = DBManager::Add();
                    DBManager::disconnect();
                    return ($data === 'Inserted')?Response::json(200, "Visitor data saved"):Response::json(409, 'Request not processed');
                }
                else if($_SERVER['REQUEST_METHOD'] === 'GET')
                {
                    DBManager::connect();
                    $data = DBManager::Read();
                    DBManager::disconnect();
                    return ($data === "Error occured")?Response::json(400, strtoupper(self::$url[2])." missing in request"):Response::json(200, $data);
                }
                break;
            }
        }
    }

    private function History()
    {
        switch(@ explode("/", $_GET['controller'])[3])
        {
            case "Save":
            {
                $_POST['Date'] = date('Y-m-d');
                $_POST['Time'] = date('H:m:s');
                $data = json_encode(mapping($_POST, array('Path', 'Method', 'Staus', 'Browser', 'Date', 'Time')), JSON_PRETTY_PRINT);
                return (General::saveRequestHistory(explode("/", $_GET['controller'])[4], $data))?Response::json(200, "User history saved"):Response::json(409, "Request not saved");
                break;
            }
            default:
            {
                return Response::json(200, General::getRequestHistory(explode("/", $_GET['controller'])[3]));
                break;
            }
        }
    }

    private function Logs()
    {
        DBManager::$table = strtolower('servicelogs');
        self::$url = explode('/', $_GET['controller']);
        // DBManager::$key[0] = self::$url[2];
        // DBManager::$data = array(self::$url[2] => self::$url[3]);
        DBManager::connect();
        $data = DBManager::Read();
        DBManager::disconnect();
        return Response::json(200, $data);
    }
}


?>