<?php

Trait Manager
{
    use DBManager;
    static $map = array(),
                   $response;

    final static function init()
    {
        $type = DB_TYPE;
        return self::$type();
    }
    
    final static function Search()
    {
        DBManager::connect();
        $response = Response::json(200, DBManager::Search());
        DBManager::disconnect();
        return $response;
    }

    final static function nosql()
    {
        include('nosql.php');
        return nosql::requestinit();
    }

    final static function sql()
    {
        include('sql.php');
        return sql::requestinit();
    }
}

Trait TAuth
{
    use DBManager;
    private static $dbtable, $response;
    
    final private function validate($pass = null, $auth = null, $salt = null) 
    {
        $password = $auth['password'];
        unset($auth['password']);
        unset($auth['id']);
        return ($password !== _create_hash($pass, $salt))
                ? Response::json(401, "Incorrect username or password")
                : Response::json(200, 
                    array(
                        "token" => Token::generateToken(@ $auth['access'], @ $GLOBALS['HEADERS']['X-Token']), 
                        "data" => $auth,
                    )
                );
    }

    final private static function getAuthData($arr = array())
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        DBManager::$data[DBManager::$key[0]] = $arr[DBManager::$key[0]];
        $user = DBManager::Read()[0];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        return (!is_array($user)) 
                ? (string) "User not found"
                : (array) $user;
    }

    final static function authInit($func = null, $key = 'username')
    {
        self::$dbtable = strtolower(DBManager::$table);
        DBManager::$table = strtolower(DBManager::$table."auth");
        DBManager::$key[0] = $key;
        switch($func)
        {
            case "Login":
            {
                if($_SERVER['REQUEST_METHOD'] !== 'POST')
                {
                    return Response::json(405, "Request method not supportted by request");
                }
                DBManager::connect();
                unset(DBManager::$key[1]);
                unset(DBManager::$key[2]);
                $auth = self::getAuthData($_POST);
                self::$response = (is_string($auth))
                                    ? Response::json(401, "Incorrect username or password")
                                    : self::validate($_POST['password'], $auth, SALT);
                                    DBManager::disconnect();
                break;
            }
            case "Register":
            {
                if($_SERVER['REQUEST_METHOD'] !== 'POST')
                {
                    return Response::json(405, "Request method not supportted by request");
                }
                DBManager::connect();
                unset(DBManager::$key[1]);
                $_POST['password'] = _create_hash($_POST['password'], SALT);
                Manager::$map = array("username", "password", "authid", "status", "access");
                DBManager::$data = mapping($_POST, Manager::$map);
                self::$response = (DBManager::Add() === 'Inserted') 
                                    ? Response::json(200, "User credentials Addd") 
                                    : Response::json(409, "User credentials exists") ;
                                    DBManager::disconnect();
                break;
       
            }
            case "Remove":
            {
                if($_SERVER['REQUEST_METHOD'] !== 'DELETE')
                {
                    return Response::json(405, "Request method not supportted by request");
                }
                DBManager::connect();
                $key = explode('/', $_REQUEST['controller']);
                unset(DBManager::$key[1]);
                unset(DBManager::$key[2]);
                DBManager::$key[0] = "authid";
                DBManager::$data[DBManager::$key[0]] = str_replace("-", " ", $key[3]);
                self::$response = Response::json(200, DBManager::Delete());
                DBManager::disconnect();
                break;
       
            }
            case "Auth":
            {
                if($_SERVER['REQUEST_METHOD'] !== 'GET')
                {
                    return Response::json(405, "Request method not supportted by request");
                }
                DBManager::connect();
                $key = explode('/', $_REQUEST['controller']);
                unset(DBManager::$key[1]);
                unset(DBManager::$key[2]);
                DBManager::$key[0] = "authid";
                DBManager::$data[DBManager::$key[0]] = str_replace("-", " ", $key[2]);
                self::$response = Response::json(200, DBManager::Read());
                DBManager::disconnect();
                break;
            }
            case "ForgotPassword":
            {
                if($_SERVER['REQUEST_METHOD'] !== 'PUT')
                {
                    return Response::json(405, "Request method not supportted by request");
                }
                DBManager::connect();
                $key = explode('/', $_REQUEST['controller']);
                unset(DBManager::$key[1]);
                unset(DBManager::$key[2]);
                DBManager::$key[0] = "authid";
                DBManager::$data[DBManager::$key[0]] = str_replace("-", " ", $key[2]);
                parse_str(file_get_contents("php://input"), $GLOBALS['PUT']);
                $GLOBALS['PUT'] = mapping($GLOBALS['PUT'], Manager::$map);
                self::$response = Response::json(200, DBManager::Update());
                DBManager::disconnect();
                break;
            }
            default:
            {
                throw new exception("Bad authentcation request call");
                break;
            }
        }
        return self::$response;
    }
}
?>


