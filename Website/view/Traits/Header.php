<!DOCTYPE html>
<html lang="en" ng-app="angApp">
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
    <meta itemprop="description" content="<?php echo $_GET['description']; ?>">
    <meta itemprop="image" content="<?php echo $_GET['image']; ?>">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    
    <script src="<?php echo $GLOBALS['config']['CDN']; ?>assets/js/loader.js" type="text/javascript"></script>   
    <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN'] ?>assets/css/style.css">
    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN'] ?>assets/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN'] ?>assets/vendors/bower_components/animate.css/animate.min.css">
    <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN'] ?>assets/vendors/bower_components/jquery.scrollbar/jquery.scrollbar.css">
    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN'] ?>assets/css/app.min.css">
    
 </head>
    
 <body data-ma-theme="indigo" class="bg-white">
    <main class="main">
        <div class="page-loader" ng-if="dataLoading" id="preloader">
            <div class="page-loader__spinner">
                <svg viewBox="25 25 50 50">
                    <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
                </svg>
            </div>
        </div>

        <header class="header bg-transparent no-shadow">
            <div class="navigation-trigger visible-xs" data-ma-action="aside-open" data-ma-target=".sidebar">
                <div class="navigation-trigger__inner">
                    <i class="navigation-trigger__line"></i>
                    <i class="navigation-trigger__line"></i>
                    <i class="navigation-trigger__line"></i>
                </div>
            </div>

            <div class="header__logo">
                <h1><a href="#" class="fs-20 text-black">Website</a></h1>
            </div>

            <ul class="nav pull-right">
                <li class="nav-item hidden-xs">
                    <a class="nav-link" href="<?php echo $GLOBALS['config']['SITE'] ?>" title="Home Page" >Home</a>
                </li>
                <li class="nav-item hidden-xs">
                    <a class="nav-link" href="<?php echo $GLOBALS['config']['SITE'] ?>News" title="News Page" >News</a>
                </li>
                <li class="nav-item hidden-xs">
                    <a class="nav-link" href="<?php echo $GLOBALS['config']['SITE'] ?>Accounts" title="Accounts Page" >Accounts</a>
                </li>
                <li class="nav-item hidden-xs">
                    <a class="nav-link" href="<?php echo $GLOBALS['config']['SITE'] ?>Accounts/Logout" title="Logout Page" >Logout</a>
                </li>
                <li class="nav-item hidden-xs">
                    <a class="nav-link" href="<?php echo $GLOBALS['config']['SITE'] ?>Contact-Us" title="Contact Page" >Contact Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" data-target="#collapse1">
                        <i class="zmdi zmdi-search"></i>
                    </a>
                </li>
            </ul>

            <div id="collapse1" class="bg-transparent no-shadow container top-menu collapse in">
                <form class="search">
                    <div class="search__inner">
                        <input type="text" class="search__text" placeholder="Search for people, files, documents...">
                        <i class="zmdi zmdi-search search__helper" data-ma-action="search-close"></i>
                    </div>
                </form>
            </div>
        </header>
        