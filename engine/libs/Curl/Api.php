<?php

// class ApiCaller
// {
// 	public static function sendRequest($controller = null, $request = null)
// 	{
// 		includeFile("application/controllers/{$controller}.php");
// 		$control = new $controller();
// 		$response = $control->processLogic($request);
//         header('Content-Type: text/html');
// 		unset($control);
// 		return $response;
// 	}
// }



class ApiCaller
{
	//some variables for the object
	private static $headers;
		
	//if the results are valid
	public static function sendRequest($token = NULL, $key = null, $controller = null, $request = array(), $method = null)
	{
		self::$headers = [
            "Accept-language: en",
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded",
			"X-Token: {$key}",
			"host: locahost",
			"User-Agent: Twitterbot"
		];
        self::$headers['Authorization'] = (!empty($token)) ? "Bearer {$token}": null;
		//$enc_request = parent::secureData("Encode", $request);
		//initialize and setup the curl handler
		$ch = curl_init();
		switch($method)
		{
			case "POST":
			{
				curl_setopt($ch, CURLOPT_URL, $GLOBALS['config']['API'].$controller);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
				break;
			}
			case "GET":
			{
				unset(self::$headers[3]);
				curl_setopt($ch, CURLOPT_URL, $GLOBALS['config']['API'].$controller);
				curl_setopt($ch, CURLOPT_HTTPGET, 1);
				break;	
			}
		}
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::$headers);
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
        //header('Content-Type: text/html');
		if($err)
		{
			return $err;
		} 
		else 
		{
			return @ json_encode(json_decode($response)->Output);
		}
	}
}

?>