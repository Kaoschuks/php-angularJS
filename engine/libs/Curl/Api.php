<?php

Abstract class ApiCaller
{
	//some variables for the object
	private static $headers;
		
	//if the results are valid
	public static function sendRequest($token = NULL, $key = null, $controller = null, $request = array(), $method = null)
	{
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
				curl_setopt($ch, CURLOPT_URL, $GLOBALS['config']['API'].$controller);
				curl_setopt($ch, CURLOPT_HTTPGET, 1);
				break;	
			}
		}
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			//"Authorization: Bearer {$token}",
            "Accept-language: en",
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded",
			//"X-Token: {$key}",
			//"host: locahost",
			//"User-Agent: Twitterbot"
		]);
		
		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		return ($err) 
				? $err
				: $response;
	}
}

?>