"use strict";

angular
    .module('app.controllers', [])
    .controller("mainCtrl", mainController)
    .controller("errCtrl", errorController)
    .controller("settingCtrl", settingsController);

function mainController($scope, modules, $state, localStorageService, $rootScope, BasicService, $location, siteFunctions, dashboardFunctions) 
{
    var app = this; 
    app.saveSocialAnalyticsConfig = saveSocialAnalyticsConfig;
    
    $scope.user = $rootScope.globals.currentUser.username;
    app.DashboardData = {};
    
    (function initController() {
        // get all user data
        switch($state.current.name)
        {
            case "Dashboard":
            {
                app.DashboardData['Username'] = $rootScope.globals.currentUser.username;
                getDashboardData();
                siteFunctions.chartJS("line-chart");
                break;
            }
            case "Seo":
            {
                getSocialAnalyticsConfig();
                siteFunctions.chartJS("line-chart");
                break;
            }
            case "Logs":
            {
                BasicService.GetData("Analytics", "/Logs", function(response)
                {
                    if (response.Status == 200) {
                        app.serverLogs = {} = response.Output;
                        $rootScope.dataLoading = false;
                    } else {
                        $scope.Error = response.Output;
                        $rootScope.dataLoading = false;
                    }
                });
                BasicService.GetData("Analytics", "/Visitor/View", function(response)
                {
                    if (response.Status == 200) {
                        app.pageview = {} = response.Output;
                        $rootScope.dataLoading = false;
                    } else {
                        $scope.Error = response.Output;
                        $rootScope.dataLoading = false;
                    }
                });
                BasicService.GetData("Analytics", "/Visitor", function(response)
                {
                    if (response.Status == 200) {
                        app.visitors = {} = response.Output;
                        $rootScope.dataLoading = false;
                    } else {
                        $scope.Error = response.Output;
                        $rootScope.dataLoading = false;
                    }
                });
                // siteFunctions.chartJS("line-chart");
                break;
            }
            case "Lockscreen":
            {
                $rootScope.globals.currentUser.authData = null;
                window.location.href = "Lockscreen";
                break;
            }
        }
    })();

    function getDashboardData()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("/Analytics", "/Visitor", function(response)
        {
            if (response.Status == 200) {
                app.DashboardData['visitors'] = response.Output;
                $rootScope.dataLoading = false;
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
        BasicService.GetData("Posts", "/Comments", function(response)
        {
            if (response.Status == 200) {
                app.DashboardData['comments'] = {} = response.Output;
                BasicService.GetData("Posts/Comments", "/status/approved", function(response)
                {
                    if (response.Status == 200) {
                        app.DashboardData['comments']['approved'] = {} = response.Output.count;
                        $rootScope.dataLoading = false;
                    } else {
                        $scope.Error = response.Output;
                        $rootScope.dataLoading = false;
                    }
                });
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
        $.each(modules, function(index, values){
            values = (values === "Email") ? "Email/Subscribers": values;
            BasicService.GetData("", values, function(response)
            {
                if (response.Status == 200) {
                    app.DashboardData[(values === "Email/Subscribers") ? "Subscribers": values] = {} = response.Output;
                    $rootScope.dataLoading = false;
                } else {
                    $scope.Error = response.Output;
                    $rootScope.dataLoading = false;
                }
            });
        });
        BasicService.GetData("Posts", "/status/approved", function(response)
        {
            if (response.Status == 200) {
                app.DashboardData['Posts']['approved'] = {} = response.Output.count;
                $rootScope.dataLoading = false;
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
        BasicService.GetData("Accounts", "/status/activated", function(response)
        {
            if (response.Status == 200) {
                app.DashboardData['Accounts']['approved'] = {} = response.Output.count;
                $rootScope.dataLoading = false;
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
        BasicService.GetData("Analytics", "/Logs", function(response)
        {
            if (response.Status == 200) {
                app.DashboardData['serverLogs'] = {} = response.Output;
                $rootScope.dataLoading = false;
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
        // BasicService.GetData("Analytics", "/Server", function(response)
        // {
        //     if (response.Status == 200) {
        //         app.DashboardData['serverLogs'] = {} = response.Output.count;
        //         $rootScope.dataLoading = false;
        //     } else {
        //         $scope.Error = response.Output;
        //         $rootScope.dataLoading = false;
        //     }
        // });
        // dashboardFunctions.firewallLogs(function(data)
        // {
        //     app.DashboardData['firewallLogs'] = data;
        // });
        // dashboardFunctions.errorLogs(function(data)
        // {
        //     app.DashboardData['errorLogs'] = data;
        // });
        // dashboardFunctions.socialLikes(function(data)
        // {
        //     app.DashboardData['socialLikes'] = data;
        // });
    }

    function getSocialAnalyticsConfig()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("/Configs", "?action=getSocialAnalytics&siteUrl=UI/config", function(response)
        {
            if (response.Status == 200) {
                $scope.Error = null;
                app.fbtitle = response.Output.getSocialAnalytics.facebooktitle;
                app.fbdesc = response.Output.getSocialAnalytics.facebookdescription;
                app.fbimage = response.Output.getSocialAnalytics.facebookimage;
                app.fbsimage = response.Output.getSocialAnalytics.facebooksecureimage;
                app.fbadmin = response.Output.getSocialAnalytics.facebookadmin;
                app.fbtype = response.Output.getSocialAnalytics.facebooktype;
                app.fbpageurl = response.Output.getSocialAnalytics.facebookurl;
                app.fbsitename = response.Output.getSocialAnalytics.facebooksitename;
                app.gpbaname = response.Output.getSocialAnalytics.googleauthor;
                app.gpbname = response.Output.getSocialAnalytics.googlename;
                app.gppagename = response.Output.getSocialAnalytics.googlepage;
                app.gppageurl = response.Output.getSocialAnalytics.googlepublisherurl;
                app.gpimageurl = response.Output.getSocialAnalytics.googleimage;
                app.googleanalytics = response.Output.getSocialAnalytics.googleanalytics;
                app.binganalytics = response.Output.getSocialAnalytics.binganalytics;
                app.yandexanalytics = response.Output.getSocialAnalytics.yandexanalytics;
                $rootScope.dataLoading = false;
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
    }

    function saveSocialAnalyticsConfig()
    {
        $rootScope.dataLoading = true;
        BasicService.PostData
        (
            "/Configs", 
            { 
                action: "saveSocialAnalytics", 
                file: "UI/config/socialSeo.ini",
                facebooktitle: app.fbtitle,
                facebookdescription: app.fbdesc,
                facebookimage:app.fbimage,
                facebooksecureimage: app.fbsimage,
                facebookadmin: app.fbadmin,
                facebooktype: app.fbtype,
                facebookurl: app.fbpageurl,
                facebooksitename: app.fbsitename,
                googleauthor: app.gpbaname,
                googlename: app.gpbname,
                googlepage: app.gppagename,
                googlepublisherurl: app.gppageurl,
                googleimage: app.gpimageurl,
                googleanalytics: app.googleanalytics,
                binganalytics: app.binganalytics,
                yandexanalytics: app.yandexanalytics,
            }, 
            function(response)
            {
                if (response.Status == 200) {
                    $scope.Error = null;
                    $rootScope.dataLoading = false;
                    getSocialAnalyticsConfig();
                } else {
                    $scope.Error = response.Output;
                    $rootScope.dataLoading = false;
                }
            }
        );
    }
};

function errorController($scope, $state, $location)
{
    (function initController() {
        //$location.path('Login');
    })();
    
    function proccessErrPage(location)
    {
        switch(location)
        {
            case "/404":
            {
                $scope.pageDescription = "Page not found";
                break;
            }
            case "/500":
            {
                $scope.pageDescription = "Internal server error";
                break;
            }
        }
    }
};
