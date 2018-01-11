<?php
class Logger
{
    public function log($type = null, $data = null)
    {
        try
        {
            switch($type)
            {
                case "get_log":
                {
                    $this->response = self::get_log();
                    break;
                }
                case "save_log":
                {
                    $this->response = self::save_log($data);
                    break;
                }
                default:
                {
                    $this->response = "Wrong logging operation services was called";
                }
            }
        }
        catch(Exception $e)
        {
            return $e->getMessage()." occured in logging operation";
        }
        
        return $this->response;
    }
    
    private function get_log()
    {
        $response = read_file(getenv('SERVICE_LOG'), "");
        $this->response['services'] = array_reverse(explode("\n\n", $response));
        $resp = read_file(getenv('FIREWALL_LOG'), "");
        $this->response['firewall'] = array_reverse(explode("\n\n", $resp));
        $resp = read_file(getenv('ERROR_LOG'), "");
        $this->response['error'] = array_reverse(explode("\n\n", $resp));
        return $this->response;
    }
    
    private function save_log($service = null)
    {
        $date = date("d:m:y");
        $time = date("G.i:s", time());
        $log = "Request action : ".$service['response']['action']." of Controller : ".$service['response']['controller']." and request method of ".$_SERVER['REQUEST_METHOD']." with status message ".strtoupper($service['Message'])." was requested by IP ".$_SERVER['REMOTE_ADDR']." and server status {$service['Status']} on {$date} by {$time}. \n\n";
        $response = update_file(Server_Root.getenv('SERVICE_LOG'), $log);
        return "Service logged";
    }
}

?>