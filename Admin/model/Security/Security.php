<?php

interface IBasicSecurity
{
    public function securityLogic($request = array());
}

class BasicSecurity implements IBasicSecurity
{
    private $request_uri = null,
            $query_string = null,
            $user_agent = null,
            $seconds_to_cache = null,
            $lastModified = null,
            $etagFile = null,
            $etagHeader = null,
            $request = null,
            $ifModifiedSince = null,
            $includes = null,
            $xss = null,
            $RFI = null,
            $files = array(
                "Firewall" => 'model/Security/firewall.php',
                "RFI" => 'model/Security/RFI.php',
                "XSS" => 'model/Security/xss_clean.php',
            );

    function __construct()
    {        
        $this->request_uri = $_SERVER['REQUEST_URI'];
        $this->query_string = $_SERVER['QUERY_STRING'];
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
    }    

    function __destruct()
    {
        unset($this);
    }

    public function securityLogic($request = array())
    {
        try
        {
            header_remove('X-Powered-By');
            header_remove('Server');
            header("edit Set-Cookie: HttpOnly;Secure");
            ini_set('expose_php', 'off');
            //Set-Cookie (.*) "$1;HttpOnly;Secure"
            //ini_set('session.cookie_httponly', 1);

            if(@ fsockopen($_SERVER['REMOTE_ADDR'], 80, $errstr, $errno, 1))
            throw new Exception("Proxy access not allowed");
            
            self::malicous_request();
            if($_SERVER['REQUEST_SCHEME'] === "https")
            {
                self::security_headers();
            }
            self::cors_header();
            define('PHP_FIREWALL_REQUEST_URI', strip_tags( $_SERVER['REQUEST_URI']));
            define('PHP_FIREWALL_ACTIVATION', true );
            includeFile($this->files['Firewall']);
            includeFile($this->files['RFI']);
            self::protectRFI();
            includeFile($this->files['XSS']);
            $this->request = self::cleanInputs($request);
            self::caching_header();
            return (array)$this->request;
        }
        catch(Exception $ex)
        {
            update_file(getenv('ERROR_LOG'), $ex->getMessage()."\n\n");
            self::denyAccess();
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
        $this->xss = new xssClean();
        $data = (string)$this->xss->clean_input($rawData);
        unset($this->xss);
        return $data;
    }

    private function protectRFI()
    {
        $this->RFI = new rfistop();
        echo $this->RFI->rfidurdur("header","403");
    }

    private function malicous_request()
    {
        if (	//strlen($this->request_uri) > 255 || 
            stripos($this->request_uri, 'eval(') || 
            stripos($this->request_uri, 'CONCAT') || 
            stripos($this->request_uri, 'UNION+SELECT') || 
            stripos($this->request_uri, '(null)') || 
            stripos($this->request_uri, 'base64_') || 
            stripos($this->request_uri, '/localhost') || 
            stripos($this->request_uri, '/pingserver') || 
            stripos($this->request_uri, '/config.') || 
            stripos($this->request_uri, '/wwwroot') || 
            stripos($this->request_uri, '/makefile') || 
            stripos($this->request_uri, 'crossdomain.') || 
            stripos($this->request_uri, 'proc/self/environ') || 
            stripos($this->request_uri, 'etc/passwd') || 
            stripos($this->request_uri, '/https/') || 
            stripos($this->request_uri, '/http/') || 
            stripos($this->request_uri, '/ftp/') || 
            stripos($this->request_uri, '/cgi/') || 
            stripos($this->request_uri, '.cgi') || 
            stripos($this->request_uri, '.exe') || 
            stripos($this->request_uri, '.sql') || 
            stripos($this->request_uri, '.ini') || 
            stripos($this->request_uri, '.dll') || 
            stripos($this->request_uri, '.asp') || 
            stripos($this->request_uri, '.jsp') || 
            stripos($this->request_uri, '/.bash') || 
            stripos($this->request_uri, '/.git') || 
            stripos($this->request_uri, '/.svn') || 
            stripos($this->request_uri, '/.tar') || 
            stripos($this->request_uri, ' ') || 
            stripos($this->request_uri, '<') || 
            stripos($this->request_uri, '>') || 
            stripos($this->request_uri, '/=') || 
            stripos($this->request_uri, '...') || 
            stripos($this->request_uri, '+++') || 
            stripos($this->request_uri, '://') || 
            stripos($this->request_uri, '/&&') || 
            // query strings
            stripos($this->query_string, '?') || 
            stripos($this->query_string, ':') || 
            stripos($this->query_string, '[') || 
            stripos($this->query_string, ']') || 
            stripos($this->query_string, '../') || 
            stripos($this->query_string, '127.0.0.1') || 
            stripos($this->query_string, 'loopback') || 
            stripos($this->query_string, '%0A') || 
            stripos($this->query_string, '%0D') || 
            stripos($this->query_string, '%22') || 
            stripos($this->query_string, '%27') || 
            stripos($this->query_string, '%3C') || 
            stripos($this->query_string, '%3E') || 
            stripos($this->query_string, '%00') || 
            stripos($this->query_string, '%2e%2e') || 
            stripos($this->query_string, 'union') || 
            stripos($this->query_string, 'input_file') || 
            stripos($this->query_string, 'execute') || 
            stripos($this->query_string, 'mosconfig') || 
            stripos($this->query_string, 'environ') || 
            //stripos($this->query_string, 'scanner') || 
            stripos($this->query_string, 'path=.') || 
            stripos($this->query_string, 'mod=.') || 
            // user agents
            stripos($this->user_agent, 'binlar') || 
            stripos($this->user_agent, 'casper') || 
            stripos($this->user_agent, 'cmswor') || 
            stripos($this->user_agent, 'diavol') || 
            stripos($this->user_agent, 'dotbot') || 
            stripos($this->user_agent, 'finder') || 
            stripos($this->user_agent, 'flicky') || 
            stripos($this->user_agent, 'libwww') || 
            stripos($this->user_agent, 'nutch') || 
            stripos($this->user_agent, 'planet') || 
            stripos($this->user_agent, 'purebot') || 
            stripos($this->user_agent, 'pycurl') || 
            stripos($this->user_agent, 'skygrid') || 
            stripos($this->user_agent, 'sucker') || 
            stripos($this->user_agent, 'turnit') || 
            stripos($this->user_agent, 'vikspi') || 
            stripos($this->user_agent, 'zmeu')
        ) 
        {
            self::denyAccess();
        }
    }
    
    private function cors_header()
    {
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST");
            
            if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }
        else 
        {
            self::denyAccess();
        }
        
    }

    private function security_headers()
    {
        //unset($_SERVER['HTTP_COOKIE']);
        header('Public-Key-Pins: pin-sha256="d6qzRu9zOECb90Uez27xWltNsj0e1Md7GkYYkVoZWmM=";pin-sha256="E9CZ9INDbd+2eRQozYqqbQ2yXLVKB9+xcprMF+44U1g=";max-age=604800; includeSubDomains; report-uri="https://example.net/pkp-report"');
        header("Strict-Transport-Security: max-age=31536000");
    } 

    private function caching_header()
    {
        $this->lastModified = filemtime(__FILE__);
        $this->etagFile = md5_file(__FILE__);
        $this->ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
        $this->etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

        header("Last-Modified: ".gmdate("D, d M Y H:i:s", $this->lastModified)." GMT");
        header("Etag: $this->etagFile");
        
        $this->seconds_to_cache = 3600;
        $ts = gmdate("D, d M Y H:i:s", time() + $this->seconds_to_cache) . " GMT";
        header('Expires: ' . gmdate('D, d M Y H:i:s', time()+1*4*3600) . ' GMT');
        header("Pragma: cache");
        header("Cache-Control: max-age=$this->seconds_to_cache");

        //check if page has changed. If not, send 304 and exit
        if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) === $this->lastModified || $this->etagHeader === $this->etagFile)
        {
            header("HTTP/1.1 304 Not Modified");
        }
        
    }

    private function denyAccess()
    {
        header('HTTP/1.1 403 Forbidden');
        header('location: 403');
        header('Connection: Close');
        unset($this);
        exit;
    }
}

?>