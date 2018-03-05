<?php

Abstract class nosql
{
    private static $response;

    public static function requestinit()
    {
        self::$key = explode('/', $_REQUEST['controller']);
        unset(self::$key[0]);
        $count = count(self::$key);
        DBManager::connect();
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
        DBManager::disconnect();
        return self::$response;
    }

    private static function delete()
    {
        if($count !== 2)
        {
            self::$response = 'Bad request';
        }
        DBManager::$data[self::$key[1]] = str_replace("-", " ", self::$key[2]);
        DBManager::$key = str_replace("-", " ", self::$key[1]);
        self::$response = (self::$response !== 'Bad request') 
                            ? Response::json(200, DBManager::Delete())
                            : self::$response;
    }

    private static function update()
    {
        parse_str(file_get_contents("php://input"), $GLOBALS['PUT']);
        $GLOBALS['PUT'] = mapping($GLOBALS['PUT'], Manager::$map);
        if($count !== 2)
        {
            self::$response = 'Bad request';
        }
        @ DBManager::$data[self::$key[1]] = @ str_replace("-", " ", self::$key[2]);
        @ DBManager::$key[0] = @ str_replace("-", " ", self::$key[1]);
        self::$response = (self::$response !== 'Bad request') 
                            ? Response::json(200, DBManager::Update())
                            : Response::json(400, self::$response);
    }

    private static function post()
    {
        DBManager::$data = mapping($_POST, Manager::$map);
        DBManager::$data = $_POST;
        self::$response = (DBManager::Add() === 'Inserted') 
                            ? Response::json(200, " inserted") 
                            : Response::json(200, " not inserted") ;
    }

    private static function get()
    {
        DBManager::$key = null;
        if($count % 2 == 0)
        {
            foreach(self::$key as $index => $values)
            {
                if($index % 2 === 0)
                {
                    DBManager::$data[@ self::$key[$index-1]] = str_replace("-", " ", $values);
                }elseif($index % 2 !== 0){
                    DBManager::$key[] = str_replace("-", " ", $values);
                }
            }
        }
        elseif($count % 2 != 0 && $count < 4 && $count > 1)
        {
            DBManager::$key[0] = 0;
            DBManager::$key[1] = 1;
            DBManager::$data[0] = self::$key[2];
            DBManager::$data[1] = str_replace("-", " ", self::$key[3]);
            DBManager::$cat = str_replace("-", " ", self::$key[1]);
        }
        self::$response = (self::$response !== 'Bad request') 
                            ? Response::json(200, DBManager::Read())
                            : Response::json(400, self::$response);
    }
}
?>