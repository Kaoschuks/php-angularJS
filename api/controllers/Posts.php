<?php
include_once('../model/DBM.php');
include_once('../model/FileManager.php');

Abstract Class Posts implements IController
{
    use FileManager;

    private static $entities = array('title', 'description', 'author', 'posted_on', 'image', 'category');

    static function processModule($func = null)
    {
        DBManager::$table = strtolower(__CLASS__);
        DBManager::$key[0] = "title";
        Manager::$map = self::$entities;
        return (in_array($func, get_class_methods(__CLASS__)))
                ? self::$func()
                : Manager::init();
    }
    
    private static function Search()
    {
        DBManager::$key = array('title', 'category', 'author');
        foreach(DBManager::$key as $index => $values)
        {
            DBManager::$data[$values] = $_POST['search'];
        }
        return Manager::Search();
    }

    private static function Comments()
    {
        DBManager::$key[0] = ($_SERVER['REQUEST_METHOD'] === "PUT" && $_SERVER['REQUEST_METHOD'] === "POST")
                                ? 'id'
                                : 'comment';
        $key = explode('/', $_REQUEST['controller']);
        DBManager::$table = DBManager::$table.strtolower($key[1]);
        unset($key[1]);
        $_REQUEST['controller'] = implode("/", $key);
        Manager::$map = array();
        return Manager::init();
    }

    static function Category()
    {
        $key = explode('/', $_GET['controller']);
        $count = count($key);
        if($count > 2)
        {   
            return self::processModule('');
        }
        DBManager::$table = DBManager::$table.strtolower($key[1]);
        unset($key[1]);
        $_REQUEST['controller'] = implode("/", $key);
        $_GET['controller'] = implode("/", $key);
        DBManager::$key[0] = "name";
        Manager::$map = array('name', 'description', 'sub', 'image');
        return Manager::init();
    }

    private static function Files()
    {
        return FileManager::processFiles(FileManager::$filepath.__CLASS__, 'file', '');
    }
}

?>