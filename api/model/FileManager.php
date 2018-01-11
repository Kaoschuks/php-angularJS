<?php


function processDir($dirs)
{
    $dir = array();
    foreach(scandir($dirs) as $index => $values)
    {
        $dir[$values] = (count(explode('.', $values)) == 1)
                        ? "directory"
                        : 'file';
    }
    return $dir;
}

Trait FileManager
{
    static $filepath = Server_Root.'CDN/uploads/', $dir;

    public function processFiles($path = '/', $key = null, $name)
    {
        if($dir !== null)
        {
            self::$filepath = Server_Root.$dir;
        }
        switch($_SERVER['REQUEST_METHOD'])
        {
            case "POST":
            {
                //$bulletProof = new ImageUploader\BulletProof;
                // try
                // {
                //     echo $bulletProof
                //             ->folder($path)
                //             ->fileTypes(array('gif', "jpg", "jpeg")) 
                //             ->limitSize(array("min"=>1000, "max"=>100000))
                //             ->upload($_FILES[$key]);
                // }
                // catch(Exception $e)
                // {
                    
                //     throw new Exception($e->getMessage());
                // }
                if(isset($_FILES[$key]) && !empty($_FILES[$key])) {
                    $no_files = count($_FILES[$key]['name']);
                    for ($i = 0; $i < $no_files; $i++) 
                    {
                        if ($_FILES[$key]["error"][$i] > 0) {
                            return Response::json(400, "Error: " . $_FILES[$key]["error"][$i]);
                        } else {
                            if (file_exists($path.'/'.$name.$_FILES[$key]['name'][$i])) {
                                return Response::json(409, 'File exists');
                            } else {
                                move_uploaded_file($_FILES[$key]["tmp_name"][$i], $path.'/'.$name.$_FILES[$key]['name'][$i]);
                                return Response::json(200, 'Files uploaded');
                            }
                        }
                    }
                }
                break;
            }
            case "DELETE":
            {
                return self::deleteFile($key, $path);
                break;
            }
            case "GET":
            {
                return self::getAllFilesInDirectory($path);
                break;
            }
            case "PUT":
            {
                return self::renameFile($key, $path);
                break;
            }
            default:
            {
                return Response::json(405, 'Incorrect request method used');
                break;
            }
        }
    }

    private function getAllFilesInDirectory($path = '/')
    {
        $location = str_replace(Server_Root."CDN/uploads/", null, $path).'/';
        $config = parse_ini_file(Server_Root.'engine/config/modules/Website/site.conf');
        $files = array_diff(scandir($path), array('.', '..'));
        if(is_array($files) && !empty($files))
        {
            foreach($files as $index => $filename)
            {
                $files[$index] = $config['Uploads'].$location.$filename;
            }
        }
        return !empty($files)?Response::json(200, $files):Response::json(200, array('Directory is empty'));
    }

    public function deleteFile($name = null, $path = '/')
    {
        $location = str_replace(Server_Root."CDN/uploads/", null, $path).'/';
        $config = parse_ini_file(Server_Root.'engine/config/modules/Website/site.conf');
        $url = implode('', 
            array_diff(
                array_diff(
                    explode('/', $_REQUEST['controller']),
                    explode('/', $config['Uploads'].$location)
                ),
                array('Files', 'url')
            )
        );
        return ($url === null) 
                ? Response::json(400, 'Bad Request. File request can`t be empty.') 
                : Response::json(200, delete_file($path.'/'.$url));
    }

    public static function renameFile($name = null, $path = '/')
    {
        parse_str(file_get_contents("php://input"), $_PUT);
        return ($_PUT[$name] === null && file_exists($path.'/'.$_PUT['old'])) 
                ? Response::json(400, 'Bad Request. File request can`t be empty.') 
                : Response::json(200, (@ rename($path.'/'.$_PUT['old'], $path.'/'.$_PUT[$name]))
                                    ? "File renamed"
                                    : "File does not exists");
    }
}

?>