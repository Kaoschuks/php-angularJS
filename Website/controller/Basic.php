<?php
/**
 * 
 */

class Basic
{
    public function processController($controller = null)
    {
        $controller = str_replace("-", "", $controller);
        return (in_array($controller, get_class_methods(__CLASS__)))
                ? self::$controller()
                : header("location:404");
    }

    private function AboutUs()
    {
        $_GET['keywords'] = $GLOBALS['config']['Keywords'];
        $_GET['canonical'] = $GLOBALS['config']['SITE']."About-Us";
        $_GET['title'] = "About-Us";
        $seo = json_decode(ApiCaller::sendRequest($_SESSION['jwt'], $_SESSION['token'], "Config/Pages/Website/About-Us", null, "GET"), true);
        TControllers::getSEO($seo['image'], $GLOBALS['config']['SITE'], $GLOBALS['config']['Keywords'], $seo['description'], $_GET['title']." - ".$seo['description']);
        /*ob_start();
        print eval('?>'.file_get_contents("view/Pages/AboutUs.php", TRUE));
        $page = ob_get_contents();
        ob_end_clean();
        return $page;*/
    }

    private function ContactUs()
    {
        $data = null;
        if(!empty($_POST))
        {

        }
        $_GET['keywords'] = $GLOBALS['config']['Keywords'];
        $_GET['canonical'] = $GLOBALS['config']['SITE']."Contact-Us";
        $_GET['title'] = "Contact-Us";
        $seo = json_decode(ApiCaller::sendRequest($_SESSION['jwt'], $_SESSION['token'], "Config/Pages/Website/Contact-Us", null, "GET"), true);
        TControllers::getSEO($seo['image'], $GLOBALS['config']['SITE'], $GLOBALS['config']['Keywords'], $seo['description'], $_GET['title']." - ".$seo['description']);
        /*ob_start();
        print eval('?>'.file_get_contents("view/Pages/ContactUs.php", TRUE));
        $page = ob_get_contents();
        ob_end_clean();
        return $page;*/
    }
}