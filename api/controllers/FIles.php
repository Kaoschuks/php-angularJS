<?php
include_once('../model/FileManager.php');

Abstract Class Files implements IController
{
    use FileManager;

    static function processModule($func = null)
    {
        FileManager::$dir = null;
        return (in_array($func, get_class_methods(__CLASS__)))
                ? self::$func()
                : FileManager::processFiles($path, 'file', null);
    }

    private static function dir()
    {
        return Response::json(200, "directory list");
    }

    private static function file()
    {
        return Response::json(200, "file information");
    }
}
?>