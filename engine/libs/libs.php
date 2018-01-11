<?php
// Rbac Libraries
require_once('Rbac/Rbac.php');
// Database Libraries
require_once('DB/rb.php');
require_once('DB/DBManager.php');
require_once('DB/JsonDB.class.php');
// Security Libraries
require_once('Security/Security.php');
require_once("Security/firewall.php");
require_once("Security/cryptography/SecureData.php");
require_once("Security/cryptography/LibCrypt.php");
require_once("Security/cryptography/Crypt.php");
require_once("Security/authentication/token.php");
require_once("Security/authentication/BruteForceBlock.php");
require_once("Security/authentication/VerifyEmail.php");
require_once("Security/RFI.php");
require_once("Security/xss_clean.php");
// Routing Library
require_once('Routing/routing.php');
// Curl Library
require_once('Curl/Api.php');
// PHP Mailing Library
require_once('Mailer/PHPMailerAutoload.php');
// Compression LIbraries
require_once('Compress/phpwee.php');
// Caching Libraries
require_once('Cache/phpfastcache/src/autoload.php');
require_once('Cache/Logger.php');
// Visitor Tracking Library
require_once('Tracking/tracking.php');
// Social Status Counter Library
require_once('Share/counter.php');
// Uploader Counter Library
require_once('Uploader/BulletProof.php');


const status = array(
            100 => 'Continue',  
            101 => 'Switching Protocols',  
            200 => 'OK',
            201 => 'Created',  
            202 => 'Accepted',  
            203 => 'Non-Authoritative Information',  
            204 => 'No Content',  
            205 => 'Reset Content',  
            206 => 'Partial Content',  
            300 => 'Multiple Choices',  
            301 => 'Moved Permanently',  
            302 => 'Found',  
            303 => 'See Other',  
            304 => 'Not Modified',  
            305 => 'Use Proxy',  
            306 => '(Unused)',  
            307 => 'Temporary Redirect',  
            400 => 'Bad Request',  
            401 => 'Unauthorized',  
            402 => 'Payment Required',  
            403 => 'Forbidden',  
            404 => 'Not Found',  
            405 => 'Method Not Allowed',  
            406 => 'Not Acceptable',  
            407 => 'Proxy Authentication Required',  
            408 => 'Request Timeout',  
            409 => 'Conflict',  
            410 => 'Gone',  
            411 => 'Length Required',  
            412 => 'Precondition Failed',  
            413 => 'Request Entity Too Large',  
            414 => 'Request-URI Too Long',  
            415 => 'Unsupported Media Type',  
            416 => 'Requested Range Not Satisfiable',  
            417 => 'Expectation Failed',  
            500 => 'Internal Server Error',  
            501 => 'Not Implemented',  
            502 => 'Bad Gateway',  
            503 => 'Service Unavailable',  
            504 => 'Gateway Timeout',  
            505 => 'HTTP Version Not Supported'
        );
        
function socketAccessDenied($response, $status, $msg)
{
    $response->writeHead($status, array(
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers' => 'Content-Type, User-Agent',
            'Access-Control-Allow-Methods' => 'GET',
            'Access-Control-Allow-Origin' => 'none',
            'Access-Control-Max-Age' => '86400',
            'Content-Type' => 'application/json',
            'Content-Security-Policy' => "default-src 'self'",
            'Date' => date("Y-m-d H:m:s"),
            'Strict-Transport-Security' => 'max-age=10886400; includeSubDomains; preload',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'X-Powered-By' => 'C++/11.0.2',
            'HTTP/1.1' => $status." ".status[$status],
        ));
    $response->end($msg);
}

?>