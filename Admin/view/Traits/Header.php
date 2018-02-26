<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $GLOBALS['config']['SITE']; ?>" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="application-name" content="<?php echo $GLOBALS['config']['AppName']; ?>" />
        <meta name="robots" content="<?php echo $GLOBALS['config']['Robots']; ?>">
        <title><?php echo $_GET['title']; ?></title>

        <!-- Styles -->
        <link href="<?php echo $GLOBALS['config']['CDN']; ?>assets/css/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo $GLOBALS['config']['CDN']; ?>assets/js/loader.js" ></script>
        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN']; ?>libs/bootstrap/css/glyphicon.css">        
        <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN']; ?>assets/vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN']; ?>assets/vendors/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN']; ?>assets/vendors/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN']; ?>assets/css/app.min.css">
        <link rel="stylesheet" href="<?php echo $GLOBALS['config']['CDN']; ?>libs/wysihtml5/bootstrap3-wysihtml5.min.css">

    </head>
    
    <body ng-app="adminApp" data-ma-theme="teal" ng-class="(page == '/Login') ? 'login-content bg-gray-light' : (page == '/Dashboard') ? 'bg-gray' : 'bg-white'">
        <main class="main">
            <div ng-class="(dataLoading == true) ? hidden : ''" class="page-loader">
                <div class="page-loader__spinner">
                    <svg viewBox="25 25 50 50">
                        <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
                    </svg>
                </div>
            </div>

            <header class="header bg-indigo" ng-if="sidebar !== false">

                <div class="navigation-trigger" data-ma-action="aside-open" data-ma-target=".sidebar" ng-if="page == '/Dashboard'">
                    <div class="navigation-trigger__inner">
                        <i class="navigation-trigger__line"></i>
                        <i class="navigation-trigger__line"></i>
                        <i class="navigation-trigger__line"></i>
                    </div>
                </div>
                
                <div class="header__logo hidden-sm-down" ng-if="sidebar != false">
                    <h1><a class="text-white"><?php echo $GLOBALS['config']['AppName']; ?> - <span ng-bind="title"></span></a></h1>
                </div>

                <ul class="top-nav" ng-if="sidebar !== false">
                    <li class="dropdown">
                        <div class="user">
                            <div class="user__info" data-toggle="dropdown">
                                <i class="avatar-char fs-30"><i class="zmdi zmdi-account-circle text-info"></i></i>
                                <div>
                                    <div class="user__name bold text-white" ng-bind="globals.currentUser.username">John Doe</div>
                                    <div class="user__email bold text-white" ng-bind="globals.access">Guest</div>
                                </div>
                            </div>

                            <div class="dropdown-menu m-t-40" style="position: relative; top: 40px;">
                                <a class="dropdown-item text-default" href="#">View Profile</a>
                                <a class="dropdown-item text-default" href="Settings">Settings</a>
                                <a class="dropdown-item text-default" href="Logout">Logout</a>
                            </div>
                        </div>
                    </li>
                    <li class="hidden-xs-down">
                        <a href="#" data-ma-action="aside-open" data-ma-target=".chat">
                            <i class="zmdi zmdi-wrench"></i>
                        </a>
                    </li>
                </ul>
            </header>

        