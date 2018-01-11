<?php
/**
 * Service short summary.
 *
 * Service description.
 *
 * @version 1.0
 * @author Kaos
 */
 
// included files

interface ICache
{
    public function cache_service($type = null, $sKey = null, $vData);
}

class Cache implements ICache
{
    var $iTtl = 600; // Time To Live
    var $bEnabled = false;// Time To Live
    private $response;
    
    //constructor
    function __construct()
    {
        $this->bEnabled = extension_loaded('apc');
    }
    
    public function cache_service($type = null, $sKey = null, $vData)
    {
        try
        {
            switch($type)
            {
                case "Get-Cache":
                {
                    $this->response = $this->get_data($sKey);
                    break;
                }
                case "Save-Cache":
                {
                    $this->response = $this->save_data($sKey, $vData);
                    break;
                }
                case "Delete-Cache":
                {
                    $this->response = $this->remove_data($sKey, $vData);
                    break;
                }
                default:
                {
                    $this->response = "Wrong cache operation services was called";
                }
            }
        }
        catch(Exception $e)
        {
            return $e->getMessage()." occured in cache operation";
        }
        
        return $this->response;
    }
    
    // get data from memory
    private function get_data($sKey = null)
    {
        $bRes = false;
        $vData = apc_fetch($sKey, $bRes);
        return ($bRes) ? $vData :null;
    }
    
    // save data to memory
    private function save_data($sKey = null, $vData = null)
    {
        return apc_store($sKey, $vData, $this->iTtl);
    }
    
    private function remove_data($sKey = null, $vData = null)
    {
        return (apc_exists($sKey)) ? apc_delete($sKey) : true;
    }
}
?>