<?php

    global $_HEADERS;
    define("Server_Root", "../../");
    define("KEY", parse_ini_file('config/Keys.conf')['DBKEY']);
    define("SALT", parse_ini_file('config/Keys.conf')['SALT']);
    
    function includeFile($filename)
    {
        include_once(Server_Root.$filename);
    }
    
    function minFile($jsArr = array(), $file = null)
    {
        $services;
        foreach($jsArr as $js => $files)
        {
            $files = CDN.$files;
            $services .= file_get_contents($files);
        }
        $handle = fopen($file, 'w');
        if($handle === false){
                echo 'Cannot open file:  '.$file;
        }else{
            $fw = fputs($handle, $services);
            if($fw === true){
                echo "File {$file} created and data written";
            }
            fclose($handle);
        }
    }

    function mapping($data = array(), $mapper  = array())
    {
        $maps = array();
        if(is_array($data))
        {
            foreach($data as $key => $dat)
            {
                foreach($mapper as $keys)
                {
                    if($key === $keys)
                        $maps[$key] = $dat;
                }
            }
        } 
        return $maps;
    }

    function unmapping($data = array(), $mapper  = array())
    {
        $maps = array();
        if(is_array($data))
        {
            foreach($data as $key => $dat)
            {
                foreach($mapper as $keys)
                {
                    if($key !== $keys)
                        $maps[$key] = $dat;
                }
            }
        } 
        return $maps;
    }

	function create_file($filename = null, $data = null)
    {
        $handle = fopen($filename, 'w');
        if($handle == false)
        {
            return 'Cannot open file:  '.$filename;
        }
        elseif($handle == true)
        {
            $fw = fputs($handle, $data);
            if($fw == true)
            {
                return "File created and data written";
            }
            elseif($fw == false)
            {
                return "File created but no data was writtenn to it";
            }
            fclose($handle);
        }
    }
    
    function update_file($filename = null, $data = null)
    {
        $handle = fopen($filename, 'a');
        if($handle == false)
        {
            return 'Cannot open file:  '.$filename;
        }
        elseif($handle == true)
        {
            $fw = fputs($handle, $data);
            return ($fw == true) ? "File {$filename} found and data updated" : "File {$filename} found but no data was updated to it";
            fclose($handle);
        }
    }
    
    function read_file($filename = null)
    {
        $handle = fopen($filename, 'r');
        if($handle == false)
        {
            return 'Cannot open for reading functionality';
        }
        elseif($handle == true)
        {
            $fr = fread($handle, filesize($filename));
            return (!empty($fr)) ? $fr : "File opened but no data was read";
            fclose($handle);
        }
    }
    
    function delete_file($filename = null)
    {
        return (@unlink($filename)) 
                ? "File deleted"
                : "File not deleted";
    }
    
    function accessDenied($error = null)
    {
        header('HTTP/1.1 403 Forbidden');
        header('location: 403');
        header('Connection: Close');
        exit;
    }

    function parse_ini_file_format($arr = array())
    {
        $count = count($arr);
        $parsedIni = ''; $i = 0;
        foreach($arr as $key => $value)
        {
            if($i < $count)
            {
                $parsedIni .= "{$key}={$value}\n";
            }else if($i === $count){
                $parsedIni .= "{$key}={$value}";
            }
            $i++;
        }
        return $parsedIni;
    }

    function truncateString($str, $chars, $to_space, $replacement="...") 
    {
        if($chars > strlen($str)) return $str;

        $str = substr($str, 0, $chars);
        $space_pos = strrpos($str, " ");
        if($to_space && $space_pos >= 0) 
            $str = substr($str, 0, strrpos($str, " "));

        return($str . $replacement);
    }      
    
    function generateCsrfToken()
    {
        return base64_encode(bin2hex(openssl_random_pseudo_bytes(8)));
    }   

?>