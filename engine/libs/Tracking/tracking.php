<?php

class Tracking
{
    public static function trackInfo($apiurl = null)
    {
        try
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $contents = curl_exec ($ch);
            curl_close ($ch);
            $pieces = explode(";", $contents);
            $data['country'] = @$pieces['4'] ;
            $data['city'] = @$pieces['6'] ;
            $data['city2'] = @$pieces['5'] ;
            $data['date'] = date("Y-m-d") ;
            $data['time'] = date("H:i:s") ;
            $data['ip'] = $_SERVER['REMOTE_ADDR'] ;
            $data['referer'] = isset( $_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "no referer" ;
            $data['browser'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "no User-agent" ;
            $data['url'] = $_GET['url'] ;
            $data['isbot'] = self::isBot() ? '1' : '0' ;
            return $data;
        }
        catch(Exeception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public static function isBot() 
    {
        $botlist = self::getBotList() ;
        foreach($botlist as $bot) 
        {
            if(strpos($_SERVER['HTTP_USER_AGENT'] , $bot) !== false)
            return true ;
        }
        return false ;
	}

    private static function getBotList()
    {
        if (($handle = fopen(Server_Root."engine/libs/Tracking/robotid.txt", "r")) !== FALSE) 
        {
            $count= 1 ;
            $bots = array();
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
            {	
                if (strchr($data[0] , "robot-id:")) 
                {
                    $botId = substr("$data[0]", 9) . "<br>" ;
                    array_push($bots, "$botId") ;
                    $count++ ;		
                }
            }    
            fclose($handle);
            return $bots ;
        }
	}
}


?>