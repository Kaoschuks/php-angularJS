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

        switch($_SERVER['REQUEST_METHOD'])
        {
            case "DELETE":
            {
                $key = explode('/', $_REQUEST['controller']);
                unset($key[0]);
                $count = count($key);
                if($count !== 2)
                {
                    self::$response = 'Bad request';
                }
                JSONManager::processJsonDB(
                    DBManager::$table, 
                    str_replace("-", " ", $key[1]), 
                    str_replace("-", " ", $key[2])
                );
                self::$response = (self::$response !== 'Bad request') 
                                    ? Response::json(200, JSONManager::deleteData())
                                    : Response::json(400, self::$response);
                break;
            }
            case "POST":
            {              
                DBManager::$data = mapping($_POST, Manager::$map);
                $key = (is_array(DBManager::$key)) ? DBManager::$key[0] : DBManager::$key;
                JSONManager::processJsonDB(
                    DBManager::$table, 
                    $key, 
                    $_POST
                );
                self::$response = (JSONManager::addData() === 'inserted') 
                                    ? Response::json(200, " inserted") 
                                    : Response::json(200, " not inserted") ;
                break;
            }
            case "GET":
            {
                $key = explode('/', $_REQUEST['controller']);
                unset($key[0]);
                DBManager::$key = null;
                foreach($key as $index => $values)
                {
                    if($index % 2 === 0)
                    {
                        DBManager::$data[@ strtolower((string)$key[$index-1])] = str_replace("-", " ", $values);
                    }elseif($index % 2 !== 0){
                        DBManager::$key[] = strtolower((string)str_replace("-", " ", $values));
                    }
                }
                (!empty(DBManager::$key))
                    ? JSONManager::processJsonDB(DBManager::$table, DBManager::$key[0], DBManager::$data[DBManager::$key[0]])
                    : JSONManager::processJsonDB(DBManager::$table);
                  
                $result = array_reverse(JSONManager::getData());
                self::$response = Response::json(200, empty($result) ? 'No content' : $result);
                break;
            }
            default:
            {
                throw new Exception("Invalid request method used");
            }
        }
        return self::$response;
    }

    final static function sql()
    {
        $key = explode('/', $_REQUEST['controller']);
        unset($key[0]);
        $count = count($key);
        DBManager::connect();
        switch($_SERVER['REQUEST_METHOD'])
        {
            case "DELETE":
            {
                if($count !== 2)
                {
                    self::$response = 'Bad request';
                }
                DBManager::$data[$key[1]] = str_replace("-", " ", $key[2]);
                DBManager::$key = str_replace("-", " ", $key[1]);
                self::$response = (self::$response !== 'Bad request') 
                                    ? Response::json(200, DBManager::Delete())
                                    : self::$response;
                break;
            }
            case "POST":
            {
                DBManager::$data = mapping($_POST, Manager::$map);
                DBManager::$data = $_POST;
                self::$response = (DBManager::Add() === 'Inserted') 
                                    ? Response::json(200, " inserted") 
                                    : Response::json(200, " not inserted") ;
                break;
            }
            case "PUT":
            {
                parse_str(file_get_contents("php://input"), $GLOBALS['PUT']);
                $GLOBALS['PUT'] = mapping($GLOBALS['PUT'], Manager::$map);
                if($count !== 2)
                {
                    self::$response = 'Bad request';
                }
                @ DBManager::$data[$key[1]] = @ str_replace("-", " ", $key[2]);
                @ DBManager::$key[0] = @ str_replace("-", " ", $key[1]);
                self::$response = (self::$response !== 'Bad request') 
                                    ? Response::json(200, DBManager::Update())
                                    : Response::json(400, self::$response);
                break;
            }
            case "GET":
            {
                DBManager::$key = null;
                if($count % 2 == 0)
                {
                    foreach($key as $index => $values)
                    {
                        if($index % 2 === 0)
                        {
                            DBManager::$data[@ $key[$index-1]] = str_replace("-", " ", $values);
                        }elseif($index % 2 !== 0){
                            DBManager::$key[] = str_replace("-", " ", $values);
                        }
                    }
                }
                elseif($count % 2 != 0 && $count < 4 && $count > 1)
                {
                    DBManager::$key[0] = 0;
                    DBManager::$key[1] = 1;
                    DBManager::$data[0] = $key[2];
                    DBManager::$data[1] = str_replace("-", " ", $key[3]);
                    DBManager::$cat = str_replace("-", " ", $key[1]);
                }
                self::$response = (self::$response !== 'Bad request') 
                                    ? Response::json(200, DBManager::Read())
                                    : Response::json(400, self::$response);
                break;
            }
        }
        DBManager::disconnect();
        return self::$response;
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


