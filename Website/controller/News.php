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
                self::$page = "";
                self::$description = "Posts Description";
                TControllers::$data = ApiCaller::sendRequest($_SESSION['jwt'], $_SESSION['token'], "Posts/title/{$url[2]}", null, "GET");
                self::$title = " - ";
                self::$canocical = "Posts";
                break;
            }
        }
    }

    public function processController()
    {
        self::checkState();
        self::getInterface();
    }

    private function getInterface()
    {
        TControllers::getSEO(self::$image, $GLOBALS['config']['SITE'].self::$canocical, $GLOBALS['config']['Keywords'], self::$description, $_GET['title'].self::$title);
        /*$data = TControllers::$data;
        ob_start();
        print eval('?>'.file_get_contents("../view/Pages/Home.php", TRUE));
        $page = ob_get_contents();
        ob_end_clean();
        return $page;*/
    }
}


?>