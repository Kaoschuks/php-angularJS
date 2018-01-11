<?php

includeFile('model/Security/SecureData.php');
class ApiCaller extends DataSecurity
{
	//some variables for the object
	private $_url;
	
	//construct an ApiCaller object, taking an
	//APP ID, APP KEY and API URL parameter
	function __construct($token = NULL, $controller = NULL, $key = null)
	{
		$this->token = $token;
		$this->_url = $GLOBALS['config']['API'].$controller;
		$this->headers = [
			"Authorization: Bearer {$token}",
            "Accept-language: en",
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded",
			"X-Token: {$key}",
			"host: locahost",
			"User-Agent: Twitterbot"
		];
	}

	function __destruct()
	{
		flush();
		unset($this);
	}
	
	//send the request to the API server
	//also encrypts the request, then checks
	//if the results are valid
	public function sendRequest($request, $method = null)
	{
		//$enc_request = parent::secureData("Encode", $request);
		//initialize and setup the curl handler
		$ch = curl_init();
		switch($method)
		{
			case "POST":
			{
				curl_setopt($ch, CURLOPT_URL, $this->_url);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
				break;
			}
			case "GET":
			{
				unset($this->headers[3]);
				$this->_url = $this->_url.$request;
				curl_setopt($ch, CURLOPT_URL, $this->_url);
				curl_setopt($ch, CURLOPT_HTTPGET, 1);
				break;	
			}
		}
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		if($err)
		{
			return "Error";
		} 
		else 
		{
			return json_encode(json_decode($response)->Output);
		}
	}
}

?>