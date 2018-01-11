<?php

Trait DBManager
{
    public static $config = array(),
                     $key = array(),
                     $table, $data, $cat, $db, $query, $previous;

    static function connect()
    {
        self::$config['dbname'] = (self::$db !== 'General') ? self::$db : self::$config['dbname'];
        self::$config = parse_ini_file("db.conf");
        if(!R::testConnection())
        {
            R::setup("mysql:host=".self::$config['host'].";dbname=".self::$config['dbname'], self::$config['user'], self::$config['password']);
            R::setAutoResolve( TRUE );
        }
    }

    static function disconnect()
    {
        R::close();
    }

    public static function Read()
    {
        R::freeze(1);
        if(self::$cat !== null)
        {
            self::$query = "select * from ".self::$table." where " .self::$cat." = '".self::$data[0]."' and " .self::$cat." = '".self::$data[1]."'  order by id desc ";
        }
        elseif(self::$cat === null)
        {
            self::$query = (empty(self::$key) || !isset(self::$key)) 
                        ? 'select * from '.self::$table.' order by id desc' 
                        : /*(count(self::$key) === 1) 
                            ? 'select '.@self::$key[0].' from '.self::$table.' order by id desc'
                            : */self::gen_query(self::$key);
        }
        $readData =  R::getAll(self::$query);
        return (is_array($readData) && !empty($readData)) ? $readData : 'No content';
    }

    public static function Search()
    {
        self::$query = "select * from ".self::$table." where ";
        $count = count(self::$key);
        foreach(self::$key as $key => $values)
        {
            self::$query .= ($count-1 !== $key) 
                        ? $values." like '".self::$data[$values]."' or " 
                        : $values." like '".self::$data[$values]."' order by id desc ";
        }
        $readData = R::getAll(self::$query);
        return (is_array($readData) && !empty($readData)) ? $readData: 'No content';
    }

    public static function Add()
    {
        //R::freeze(1);
        $data = R::dispense(self::$table);
        foreach(self::$data as $index => $values)
        {
            $data->$index = $values;
        }
        return (is_numeric(R::store($data))) ? "Inserted" : "Not Inserted";
    }

    public static function Save()
    {
        $res = DBManager::Read();
        return ($res === 'No content') ? DBManager::add() : "Not Inserted";
    }

    public static function Delete()
    {
        $query = "delete from ".self::$table." where ";
        if(is_array(self::$key))
        {
            foreach(self::$key as $key => $values)
            {
                $query .= (count(self::$key)-1 !== $key) 
                            ? self::$key[$key]." = '".self::$data[self::$key[$key]]."' and " 
                            : self::$key[$key]." = '".self::$data[self::$key[$key]]."'";
            }
        }
        else{
            $query = $query.self::$key." = '".self::$data[self::$key]."'";;
        }
        $data = R::exec($query);
        return (!empty($data)) ? 'Deleted': 'Not deleted';
    }

    public static function Update()
    {
        $data = self::Read()[0];
        foreach($GLOBALS['PUT'] as $key => $values)
        {
            $data[$key] = $values;
        }    
        self::$data = null;   
        self::$data = self::arrayprocess($data, array()); 
        return (self::Add() === 'Inserted') ? "Updated" : "Not updated";
    }

    final private function gen_query($dbKey)
    {
        $query = "select * from ".self::$table." where ";
        foreach($dbKey as $key => $values)
        {
            $query .= (count($dbKey)-1 !== $key) 
                        ? $dbKey[$key]." = '".self::$data[$dbKey[$key]]."' and " 
                        : $dbKey[$key]." = '".self::$data[$dbKey[$key]]."'  order by id desc ";
        }
        return $query;
    }
    
    function arrayprocess($oldarray, $newarray)
    {
        foreach($oldarray as $key => $values)
        {
            if(is_string($values))
            {
                $newarray[$key] = $values;
            }
        }
        return $newarray;
    }
}


?>