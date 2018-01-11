<?php


interface IBasicSecurity
{
    function securityLogic($request = array());
}

class BasicSecurity implements IBasicSecurity
{
    private $response;
    private static $user_agent, $request_uri, $query_string;
    public function securityLogic($request = array())
    {
        // if(@ fsockopen($_SERVER['REMOTE_ADDR'], 80, $errstr, $errno, 1))
        // throw new Exception("Proxy access not allowed");
        self::$request_uri = @ $_SERVER['REQUEST_URI'];
        self::$query_string = @ $_SERVER['QUERY_STRING'];
        self::$user_agent = @ $_SERVER['HTTP_USER_AGENT'];
        try
        {
            self::malicous_request();
            self::protectRFI();
            return (array)self::cleanInputs($request);
        }
        catch(Exception $ex)
        {
            self::denyAccess($ex->getMessage());
        }
    }

    private function cleanInputs($data)
    {
		$clean_input = array();
		if(is_array($data))
        {
            foreach($data as $k => $v)
            {
                $clean_input[$k] = self::cleanInputs($v);
            }
		}
        else
        {
            if(get_magic_quotes_gpc())
            {
                $clean_input = self::protectXSS($data);
            }
            elseif(!get_magic_quotes_gpc())
            {
                $clean_input = self::protectXSS($data);
            }
        }
		return $clean_input;
    }

    private function protectXSS($rawData = null)
    {
        $xss = new xssClean();
        return (string)$xss->clean_input($rawData);
    }

    private function protectRFI()
    {
        $RFI = new rfistop();
        echo $RFI->rfidurdur("header","403");
    }

    private function malicous_request()
    {
        if (	//strlen(self::$request_uri) > 255 || 
            stripos(self::$request_uri, 'eval(') || 
            stripos(self::$request_uri, 'CONCAT') || 
            stripos(self::$request_uri, 'UNION+SELECT') || 
            stripos(self::$request_uri, '(null)') || 
            stripos(self::$request_uri, 'base64_') || 
            stripos(self::$request_uri, '/localhost') || 
            stripos(self::$request_uri, '/pingserver') || 
            stripos(self::$request_uri, '/config.') || 
            stripos(self::$request_uri, '/wwwroot') || 
            stripos(self::$request_uri, '/makefile') || 
            stripos(self::$request_uri, 'crossdomain.') || 
            stripos(self::$request_uri, 'proc/self/environ') || 
            stripos(self::$request_uri, 'etc/passwd') || 
            stripos(self::$request_uri, '/https/') || 
            stripos(self::$request_uri, '/http/') || 
            stripos(self::$request_uri, '/ftp/') || 
            stripos(self::$request_uri, '/cgi/') || 
            stripos(self::$request_uri, '.cgi') || 
            stripos(self::$request_uri, '.exe') || 
            stripos(self::$request_uri, '.sql') || 
            stripos(self::$request_uri, '.ini') || 
            stripos(self::$request_uri, '.dll') || 
            stripos(self::$request_uri, '.asp') || 
            stripos(self::$request_uri, '.jsp') || 
            stripos(self::$request_uri, '/.bash') || 
            stripos(self::$request_uri, '/.git') || 
            stripos(self::$request_uri, '/.svn') || 
            stripos(self::$request_uri, '/.tar') || 
            stripos(self::$request_uri, ' ') || 
            stripos(self::$request_uri, '<') || 
            stripos(self::$request_uri, '>') || 
            stripos(self::$request_uri, '/=') || 
            stripos(self::$request_uri, '...') || 
            stripos(self::$request_uri, '+++') || 
            stripos(self::$request_uri, '://') || 
            stripos(self::$request_uri, '/&&') || 
            // query strings
            stripos(self::$query_string, '?') || 
            stripos(self::$query_string, ':') || 
            stripos(self::$query_string, '[') || 
            stripos(self::$query_string, ']') || 
            stripos(self::$query_string, '../') || 
            stripos(self::$query_string, '127.0.0.1') || 
            stripos(self::$query_string, 'loopback') || 
            stripos(self::$query_string, '%0A') || 
            stripos(self::$query_string, '%0D') || 
            stripos(self::$query_string, '%22') || 
            stripos(self::$query_string, '%27') || 
            stripos(self::$query_string, '%3C') || 
            stripos(self::$query_string, '%3E') || 
            stripos(self::$query_string, '%00') || 
            stripos(self::$query_string, '%2e%2e') || 
            stripos(self::$query_string, 'union') || 
            stripos(self::$query_string, 'input_file') || 
            stripos(self::$query_string, 'execute') || 
            stripos(self::$query_string, 'mosconfig') || 
            stripos(self::$query_string, 'environ') || 
            stripos(self::$query_string, 'scanner') || 
            stripos(self::$query_string, 'path=.') || 
            stripos(self::$query_string, 'mod=.') || 
            // user agents
            stripos(self::$user_agent, 'binlar') || 
            stripos(self::$user_agent, 'casper') || 
            stripos(self::$user_agent, 'cmswor') || 
            stripos(self::$user_agent, 'diavol') || 
            stripos(self::$user_agent, 'dotbot') || 
            stripos(self::$user_agent, 'finder') || 
            stripos(self::$user_agent, 'flicky') || 
            stripos(self::$user_agent, 'libwww') || 
            stripos(self::$user_agent, 'nutch') || 
            stripos(self::$user_agent, 'planet') || 
            stripos(self::$user_agent, 'purebot') || 
            stripos(self::$user_agent, 'pycurl') || 
            stripos(self::$user_agent, 'skygrid') || 
            stripos(self::$user_agent, 'sucker') || 
            stripos(self::$user_agent, 'turnit') || 
            stripos(self::$user_agent, 'vikspi') || 
            stripos(self::$user_agent, 'zmeu')
        ) 
        {
            self::denyAccess();
        }
    }

    private function denyAccess($error)
    {
        accessDenied($error);
    }
}

?>