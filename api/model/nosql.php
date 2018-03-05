<?php

Abstract class nosql
{
    private static $response;

    public static function requestinit()
    {
        switch($_SERVER['REQUEST_METHOD'])
        {
            case "DELETE":
            {
                self::delete();
                break;
            }
            case "POST":
            {
                self::post();
                break;
            }
            case "GET":
            {
                self::get();
                break;
            }
            case "PUT":
            {
                self::update();
                break;
            }
            default:
            {
                throw new Exception("Invalid request method used");
            }
        }
        return self::$response;
    }

    private static function delete()
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
    }

    private static function update()
    {
        $key = explode('/', $_REQUEST['controller']);
        unset($key[0]);
        $count = count($key);
        parse_str(file_get_contents("php://input"), $GLOBALS['PUT']);
        $GLOBALS['PUT'] = mapping($GLOBALS['PUT'], Manager::$map);
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
                            ? Response::json(200, JSONManager::updateData())
                            : Response::json(400, self::$response);
    }

    private static function post()
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
    }

    private static function get()
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
    }
}

?>