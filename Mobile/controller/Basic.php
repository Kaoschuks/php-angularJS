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
        $_GET['title'] = "About-Us - ";
        $_GET['description'] = "";
        ob_start();
        print eval('?>'.file_get_contents("view/Pages/AboutUs.php", TRUE));
        $page = ob_get_contents();
        ob_end_clean();
        return $page;
    }

    private function ContactUs()
    {
        $data = null;
        if(!empty($_POST))
        {

        }
        $_GET['keywords'] = $GLOBALS['config']['Keywords'];
        $_GET['canonical'] = $GLOBALS['config']['SITE']."Contact-Us";
        $_GET['title'] = "Contact-Us - ";
        $_GET['description'] = "Get In Touch With Us ";
        ob_start();
        print eval('?>'.file_get_contents("view/Pages/ContactUs.php", TRUE));
        $page = ob_get_contents();
        ob_end_clean();
        return $page;
    }
}