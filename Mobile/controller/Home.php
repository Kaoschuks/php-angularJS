<?php
/**
 * 
 */

class Home
{
    use TControllers;

    public function processController()
    {
        TControllers::$data['Post'] = ApiCaller::sendRequest($_SESSION['jwt'], $_SESSION['token'], "Posts", null, "GET");
        return self::getInterface();
    }

    private function getInterface()
    {
        $seo = json_decode(ApiCaller::sendRequest($_SESSION['jwt'], $_SESSION['token'], "Config/Pages/Website/Home", null, "GET"), true);
        TControllers::getSEO($seo['image'], $GLOBALS['config']['SITE'], $GLOBALS['config']['Keywords'], $seo['description'], $_GET['title']." - ".$seo['description']);
        $data = TControllers::$data;
        ob_start();
        print eval('?>'.file_get_contents("../view/Pages/Home.php", TRUE));
        $page = ob_get_contents();
        ob_end_clean();
        return $page;
    }
}


?>