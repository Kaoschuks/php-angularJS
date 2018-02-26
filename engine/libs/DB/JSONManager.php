<?php

Abstract class JSONManager
{
    private static $db,
            $ext = '.bin',
            $dbname = '../../engine/nosql/',
            $table, $data, $key;

    public function processJsonDB($table = null, $key = null, $data = null)
    {
        self::$table = $table; // set table varriable
        self::$key = $key;
        self::$data = $data;

        self::$db = new JsonDB(self::$dbname);  // multiple file json db
        (!file_exists(self::$dbname.self::$table.'.json')) // checks if file exists
            ? self::$db->createTable(self::$table)  // creates if file doesn't exists
            : ''; 
    }

    public function getData()
    {
        if(empty(self::$data))
        {
            return self::$db->selectAll(self::$table);
        }
        else if(!empty(self::$data) && is_string(self::$data))
        {
            return self::$db->select(
                self::$table, 
                self::$key,
                self::$data
            );
        }
    }

    public function addData()
    {
        $status = (bool)(count(self::$db->select(self::$table, self::$key, self::$data[self::$key])) > 0)
            ? false
            : self::$db->insert(
                self::$table, 
                self::$data,
                true
            );
        return ($status) ? 'inserted' : 'not inserted' ;
    }

    private function updateData()
    {
        $status = (bool)self::$db->update(
            self::$table, 
            self::$data,
            true
        );
        return ($status) ? 'inserted' : 'not inserted' ;
    }

    public function deleteData()
    {
        if(empty(self::$data))
        {
            $status = (bool)self::$db->deleteAll(self::$table);
        }
        else if(!empty(self::$data) && is_string(empty(self::$data)))
        {
            $status = self::$db->delete(
                self::$table, 
                self::$key,
                self::$data
            );
        }
        echo $status;
        return ($status) 
                ? "deleted" 
                : 'not deleted' ;
    }
}

?>