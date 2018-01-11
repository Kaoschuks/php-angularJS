<?php
$config = parse_ini_file(Server_Root.'../engine/config/external/auth.conf');
$config['security_salt'] = SALT;
$config['strategy_dir'] = Server_Root.'../'.$config['strategy_dir'];
$config['Strategy']['Facebook'] = parse_ini_file(Server_Root.'../engine/config/external/facebook.conf');
$config['Strategy']['Google'] = parse_ini_file(Server_Root.'../engine/config/external/google.conf');
define('CONF_FILE', dirname(__FILE__).'/'.'opauth.conf.php');

try
{ 
	require OPAUTH_LIB_DIR.'Opauth.php';
	$Opauth = new Opauth( $config );
	unset($Opauth);
}
catch(Exception $e) 
{
    update_file(getenv('ERROR_LOG'), 'Error: ' . $e->getMessage()."\n\n");
    $result = array(
        "Status" => 500,
        "Message" => "Internal Server Error",
        "Output" => 'Error: ' . $e->getMessage(),
	);
	echo json_encode($result, JSON_PRETTY_PRINT);
	exit();
}
?>