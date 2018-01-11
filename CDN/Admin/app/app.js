"use strict";
angular
    .module('adminApp', ['ui.router', 'ngSanitize', 'LocalStorageModule', 'app.controllers', 'app.services', 'datatables'])
    .config(AppConfig)
    .constant('appURL','http://127.0.0.1:8080/Caos/api/')
    .constant('modules',['Posts', 'Accounts', 'Email'])
    .constant('facebookHandle','https://facebook.com/Caos Nigeria')
    .constant('twitterHandle','https://twitter.com/Caos Nigeria')
    .run(run);
    run.$inject = ['$rootScope', '$state', '$http', '$location', 'localStorageService', 'BasicService'];

// angular configuration function
function AppConfig($stateProvider, $urlRouterProvider, $locationProvider, localStorageServiceProvider) 
{
    // localStorageService initialization
    localStorageServiceProvider
        .setPrefix('Admin')
        .setStorageType('sessionStorage');

    $stateProvider
        .state({
            name: 'app',
            url: '/',
        })
        .state({
            name: 'Dashboard',
            url: '/Dashboard',
            templateUrl: templateURL+'Pages/dashboard.html',
            controller: 'mainCtrl',
            controllerAs: 'app',
        })
        .state({
            name: 'Logs',
            url: '/Analytics/Logs',
            templateUrl: templateURL+'Pages/Analytics/logs.html',
            controller: 'mainCtrl',
            controllerAs: 'app',
        })
        // .state({
        //     name: 'Status',
        //     url: '/Analytics/Status',
        //     templateUrl: templateURL+'Pages/Analytics/status.html',
        // })
        .state({
            name: 'Seo',
            url: '/Analytics/Seo',
            templateUrl: templateURL+'Pages/Analytics/seo.html',
            controller: 'mainCtrl',
            controllerAs: 'app',
        })
        .state({
            name: 'Pages',
            url: '/Pages',
            templateUrl: templateURL+'Pages/Interfaces/pages.html',
            controller: 'pagesCtrl',
            controllerAs: 'pages',
        })
        .state({
            name: 'Faqs',
            url: '/Faqs',
            templateUrl: templateURL+'Pages/Interfaces/faqs.html',
            controller: 'pagesCtrl',
            controllerAs: 'pages',
        })
        .state({
            name: 'Accounts',
            url: '/Accounts',
            controller: 'AccountsCtrl',
        })
        .state({
            name: 'Managers',
            url: '/Accounts/Managers',
            templateUrl: templateURL+'Pages/Accounts/managers.html',
            controller: 'AccountsCtrl',
            controllerAs: 'accounts',
        })
        .state({
            name: 'Users',
            url: '/Accounts/Users',
            templateUrl: templateURL+'Pages/Accounts/users.html',
            controller: 'AccountsCtrl',
            controllerAs: 'accounts',
        })
        .state({
            name: 'Comments',
            url: '/Comments',
            templateUrl: templateURL+'Pages/Posts/comments.html',
            controller: 'postCtrl',
            controllerAs: 'posts',
        })
        .state({
            name: 'Posts',
            url: '/Posts',
            templateUrl: templateURL+'Pages/Posts/posts.html',
            controller: 'postCtrl',
            controllerAs: 'posts',
        })
        .state({
            name: 'addPost',
            url: '/Posts/Add',
            templateUrl: templateURL+'Pages/Posts/add.html',
            controller: 'postCtrl',
            controllerAs: 'posts',
        })
        .state({
            name: 'updatePost',
            url: '/Posts/Update',
            templateUrl: templateURL+'Pages/Posts/update.html',
            controller: 'postCtrl',
            controllerAs: 'posts',
        })
        .state({
            name: 'categoryPost',
            url: '/Posts/Category',
            templateUrl: templateURL+'Pages/Posts/postcategory.html',
            controller: 'postCtrl',
            controllerAs: 'posts',
        })
        .state({
            name: 'Newsletter',
            url: '/Newsletter',
            controller: 'mailCtrl',
        })
        .state({
            name: 'Subscribers',
            url: '/Newsletter/Subscribers',
            templateUrl: templateURL+'Pages/Newsletter/subscribers.html',
            controller: 'mailCtrl',
            controllerAs: 'mail',
        })
        .state({
            name: 'Newsletters',
            url: '/Newsletter/Messages',
            templateUrl: templateURL+'Pages/Newsletter/newsletter.html',
            controller: 'mailCtrl',
            controllerAs: 'mail',
        })
        // .state({
        //     name: 'Settings',
        //     url: '/Settings',
        //     templateUrl: templateURL+'Pages/settings.html', 
        //     controller: 'settingCtrl',
        //     controllerAs: 'settings',
        // })
        // .state({
        //     name: 'Rss',
        //     url: '/Rss',
        //     templateUrl: templateURL+'Pages/rss.html',
        //     controller: 'settingCtrl',
        //     controllerAs: 'settings',
        // })
        .state({
            name: 'Logout',
            url: '/Logout',
            controller: 'authCtrl',
        })
        .state({
            name: '404',
            url: '/404',
            templateUrl: templateURL+'Pages/Error.html',
            controller: 'errCtrl',
        })
        .state({
            name: 'Lockscreen',
            url: '/Lockscreen',
            controller: 'authCtrl',
            controllerAs: 'vm',
            templateUrl: templateURL+'Pages/login.html'
        })
        .state({
            name: 'Login',
            url: '/Login',
            controller: 'authCtrl',
            controllerAs: 'vm',
            templateUrl: templateURL+'Pages/Auth/login.html'
        })
        .state({
            name: 'Forgotpassword',
            url: '/Forgot-Password',
            controller: 'authCtrl',
            controllerAs: 'vm',
            templateUrl: templateURL+'Pages/Auth/forgot.html'
        });
    // pages / angular states
    $urlRouterProvider.otherwise('/404');
    $locationProvider.html5Mode(true);
};

function run($rootScope, $state, $http, $location, localStorageService, BasicService) 
{
    if($state.current.name == 'app')
    {
        $rootScope.sidebar = false;
    }
    // keep user logged in after page refresh
    $rootScope.globals = localStorageService.get('globals');
    $rootScope.page = $location.path();
    $rootScope.$on('$locationChangeSuccess', function () 
    {
        sessionStorage.setItem('collapselock', false);
        $rootScope.page = $location.path();
        $rootScope.title = ($location.path().split('/').length > 2) 
                            ? $location.path().split('/')[1] + " " + $location.path().split('/')[2]
                            : $location.path().split('/')[1] ;
        $('div.navigation-trigger').removeClass('toggled');
        $('.sidebar').removeClass('toggled');
        if(!$rootScope.globals){
            $rootScope.sidebar = false;
            if($rootScope.page !== '/Login' && $rootScope.page !== '/Forgot-Password')
            {
                $state.go("Login");
            }
        }else {
            $rootScope.sidebar = true;
        }
    });
    $rootScope.status = ['approved', 'pending', 'activated']; 
    // site functions
    $rootScope.saveImageUrl = saveImageUrl;
    $rootScope.deleteImage = function(url)
    {
        BasicService.serverRequest(
            $state.current.url.split("/")[1]+"/Files/url/"+url,
            "",
            "DELETE",
            function(response){
                switch(response.Output)
                {
                    case "File deleted":
                    {
                        responseNotify("Success", "Remove file", response.Output);
                        BasicService.GetData($state.current.url.split("/")[1]+"/Files", "", function(response)
                        {
                            if (response.Status == 200) {
                                $rootScope.Files = response.Output;
                            } else {
                                responseNotify("Error", "Remove file", response.Output);
                            }
                        });
                        break;
                    }
                    default:
                    {
                        responseNotify("Error", "Remove file", response.Output);
                        break;
                    }
                }
            }
        )
    };
    $rootScope.clickUpload = clickUpload;
    $rootScope.cutString = cutString;
}