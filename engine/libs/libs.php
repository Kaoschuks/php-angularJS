<?php
// Database Libraries
require_once('DB/rb.php');
require_once('DB/jsonDB.php');
require_once('DB/DBManager.php');
require_once('DB/JSONManager.php');
// Security Libraries
require_once("Security/cryptography/SecureData.php");
require_once("Security/cryptography/LibCrypt.php");
require_once("Security/cryptography/Crypt.php");
require_once("Security/authentication/token.php");
require_once("Security/authentication/VerifyEmail.php");
// Routing Library
require_once('Routing/routing.php');
// Curl Library
require_once('Curl/Api.php');
// PHP Mailing Library
require_once('Mailer/PHPMailerAutoload.php');
// Caching Libraries
require_once('Cache/Logger.php');
// Uploader Counter Library
require_once('Uploader/BulletProof.php');


const DB_TYPE = 'nosql';

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

?>