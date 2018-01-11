<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $_GET['description']; ?>">
    <meta name="keywords" content="<?php echo $_GET['keywords']; ?>">
    <meta name="author" content="<?php echo $GLOBALS['config']['Author']; ?>">
    <meta name="copyright" content="<?php echo $GLOBALS['config']['Copyrights']; ?>" />
    <meta name="application-name" content="<?php echo $GLOBALS['config']['AppName']; ?>" />
    <meta name="robots" content="<?php echo $GLOBALS['config']['Robots']; ?>">
    <meta name="google-analytics" content="<?php echo $GLOBALS['seo']['googleanalytics']; ?>">
    <meta name="bing-analytics" content="<?php echo $GLOBALS['seo']['binganalytics']; ?>">
    <meta name="yandex" content="<?php echo $GLOBALS['seo']['yandexanalytics']; ?>">
    <link rel="shortcut icon" href="<?php echo $GLOBALS['config']['ShortIcon']; ?>">
    <base href="<?php echo $GLOBALS['config']['SITE']; ?>" />
    <title><?php echo $_GET['title']; ?></title>
      
    <!-- Google Plus -->
    <link rel="canonical" href="<?php echo $_GET['canonical']; ?>" />
    <link rel="author" href="<?php echo $GLOBALS['seo']['googleauthor']; ?>" />
    <link rel="publisher" href="<?php echo $GLOBALS['seo']['googlepage']; ?>"/>

    <!-- for Facebook -->          
    <meta property="og:description" content="<?php echo $_GET['description']; ?>" />
    <meta property="og:type" content="<?php echo $GLOBALS['seo']['facebooktype']; ?>" />
    <meta property="og:title" content="<?php echo $_GET['title']; ?>" />
    <meta property="og:image" content="<?php echo $_GET['image']; ?>" />
    <meta property="og:url" content="<?php echo $_GET['canonical']; ?>" />
    <meta property="og:site_name" content="<?php echo $GLOBALS['seo']['facebooksitename']; ?>" />
    <meta property="fb:admins" content="<?php echo $GLOBALS['seo']['facebookadmin']; ?>" />
      
      
    <!-- Schema.org markup for Google+ -->
    <meta itemscope itemtype="http://schema.org/<?php echo $GLOBALS['seo']['facebooktype']; ?>">
    <meta itemprop="name" content="<?php echo $_GET['title']; ?>">
    <meta itemprop="description" content="<?php $_GET['description']; ?>">
    <meta itemprop="image" content="<?php $_GET['image']; ?>">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    
    <!-- Styles -->
    <!-- <link href="<?php echo $GLOBALS['config']['CDN']; ?>css/custom.css" rel="stylesheet" type="text/css"> -->
    <!-- Libraries  -->
    <script src="<?php echo $GLOBALS['config']['CDN']; ?>libs/jquery/jquery.js" type="text/javascript"></script>
    <script src="<?php echo $GLOBALS['config']['CDN']; ?>libs/Social/js/jssocials.min.js" type="text/javascript"></script>

 </head>
    
 <body>

        