<?php
define('PERMISSION_DENIED', 0);
define('PERMISSION_READ', 1);
define('PERMISSION_ADD',  2);
define('PERMISSION_UPDATE', 4);
define('PERMISSION_DELETE', 8);

class Rbac
{
    private $users = [],
            $userFunctions = [];
    private static $user;
    private static $func;

    function __construct($user = null, $func = null)
    {
        self::$user = ucfirst($user);
        self::$func = $func;
        self::getUserModels();
        self::getUserFunctions();
    }

    function __destruct()
    {
        unset($this);
    }

    private function getUserModels()
    {
        $this->users = parse_ini_file("config/Rbac/Models/".self::$user.".ini");
    }

    private function getUserFunctions()
    {
        $this->userFunctions = parse_ini_file("config/Rbac/Functions/".self::$user.".ini");
    }

    protected function checkModelAccess($permission = NULL)
    {
        switch(in_array($permission, self::bitMask((int)$this->users[self::$func])))
        {
            case true:
            {   
                return true;
                break;
            }
            case false:
            {   
                //accessDenied($error = null);
                return false;
                break;
            }
        }
    }

    public function checkFunctionAccess($permission = NULL)
    {
        switch(in_array($permission, self::bitMask((int)$this->userFunctions[self::$func])))
        {
            case true:
            {   
                return true;
                break;
            }
            case false:
            {   
                //accessDenied($error = null);
                return false;
                break;
            }
        }
    }

    private function bitMask($mask = 0) 
    {
        if(!is_numeric($mask)) {
            return array();
        }
        $return = array();
        while ($mask > 0) {
            for($i = 0, $n = 0; $i <= $mask; $i = 1 * pow(2, $n), $n++) {
                $end = $i;
            }
            $return[] = $end;
            $mask = $mask - $end;
        }
        sort($return);
        return $return;
    }
}
?>