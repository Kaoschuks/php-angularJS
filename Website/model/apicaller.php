<?php

function apiServerCall($url = null, $request = array(), $method = null)
{
    return json_decode(ApiCaller::sendRequest($_SESSION['jwt'], $_SESSION['token'], $url, $request, $method), true)['Output'];
}

?>