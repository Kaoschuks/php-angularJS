<?php
/**
 * 
 */

class News
{    
    private static $image, $canocical, $request, $page, $title, $description;

    use TControllers;

    private function checkState()
    {
        $url = explode('/', $_GET['uri']);
        $count = count($url);
        switch($count)
        {
            case 1:
            {
                self::$page = "Posts/index.php";
                self::$description = "Posts Description";
                TControllers::$data = ApiCaller::sendRequest($_SESSION['jwt'], $_SESSION['token'], "Posts", null, "GET");
                self::$title = " - ";
                self::$canocical = "Posts";
                break;
            }
            case 2:
            {
                TControllers::$data = ApiCaller::sendRequest($_SESSION['jwt'], $_SESSION['token'], "Posts/Category", null, "GET");
                self::$canocical = "Posts/Category";
                self::$description = "Category Description";
                self::$title = "- Category Title";
                self::$page = "Posts/Category.php";
                break;
            }
            case 3:
            {
                self::$method = "GET";
                $category = strtolower($url[1]);
                self::$description = "{$url[1]} Description";
                self::$title = "- {$url[1]} Title";
                self::$request = "?action=getAllPostBy{$url[1]}&{$category}={$url[2]}";
                self::$page = "Posts{$url[1]}Post.php";
                self::$canocical = "Posts/Category/{$url[2]}";
                break;
            }
            case 4:
            {
                self::$method = "GET";
                self::$request = "?action=getSinglePost&title={$url[3]}";
                self::$page = "PostsSingle.php";
                self::$title = "- {$url[3]} Title";
                self::$canocical = "Posts/Category/{$url[2]}/{$url[3]}";
                break;
            }
        }
    }

    public function processController()
    {
        self::checkState();
        return self::getInterface();
    }

    private function getInterface()
    {
        TControllers::getSEO(self::$image, $GLOBALS['config']['SITE'].self::$canocical, $GLOBALS['config']['Keywords'], self::$description, $_GET['title'].self::$title);
        $data = TControllers::$data;
        ob_start();
        print eval('?>'.file_get_contents("../view/Pages/Home.php", TRUE));
        $page = ob_get_contents();
        ob_end_clean();
        return $page;
    }
}


?>