<?php

class DataSecurity
{
    function __construct()
    {
        $this->headers = apache_request_headers();
        $this->browser = null;
        $this->validated = null;
    }

    function  __destruct()
    {
        unset($this);
    }

    public static function secureData($type = null, $data = array())
    {
        switch($_SERVER['REQUEST_METHOD'])
        {
            case "POST":
            case "GET":
            {
                if((string) $type === "Encode")
                {
                    // to use aes cryptographic algorithm to secure data
                    //$this->data = (string) self::cryptoJsAesEncrypt($_SESSION['JWToken'], (string)base64_encode(json_encode($data)));
                    
                    // to base 64 rncode data
                    $this->data = (string) base64_encode(json_encode($data));
                }
                elseif((string) $type === "Decode")
                {
                    // to base 64 decode data
                    $this->data = @ (array) json_decode(base64_decode($data['data']), TRUE);
                    
                    // to use aes cryptographic algorithm to unsecure data
                    //$this->data = (array) self::cleanInputs(self::cryptoJsAesDecrypt($_SESSION['JWToken'], $data['data']));
                }
                break;
            }
            default:
            {
                throw new Exception("Bad Request Method Used", 0);                
            }
        }
        return $this->data;
    }

    protected function cryptoJsAesDecrypt($passphrase, $jsonString)
    {
        $jsondata = json_decode($jsonString, true);
        try 
        {
            $salt = hex2bin($jsondata["s"]);
            $iv  = hex2bin($jsondata["iv"]);
        } 
        catch(Exception $e) 
        { 
            return null; 
        }
        $ct = base64_decode($jsondata["ct"]);
        $concatedPassphrase = $passphrase.$salt;
        $md5 = array();
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) 
        {
            $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        return json_decode($data, true);
    }

    protected function cryptoJsAesEncrypt($passphrase, $value)
    {
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (strlen($salted) < 48) 
        {
            $dx = md5($dx.$passphrase.$salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv  = substr($salted, 32,16);
        $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
        $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
        return json_encode($data);
    }

    private function verifyJWT()
    {
        if(!empty(apache_request_headers()['X-Token']))
        {
            $validated = validateToken(explode(" ", $this->headers['Authorization'])[1]);
            switch(is_array($validated))
            {
                case true:
                {
                    $this->validated = true;
                    if($validated['data']->useragent === $_SERVER['HTTP_USER_AGENT'] && $validated['data']->ipaddress === $_SERVER['REMOTE_ADDR'])
                        $this->browser = true;
                    else
                        $this->browser = false;
                    break;
                }
                case false:
                {
                    $this->validated = $validated;
                    break;
                }
            }
        }
        else{
            $this->validated = "CRSF missing";
        }
    }

    public static function verifyUser()
    {
        self::verifyJWT();
        if($this->browser !== true || $this->validated !== true)
            return modelResponse(403, "Authorzation failed. Try Again");
        else
            return ["Status" => 200];
    }

}
?>