"use strict";

var templateUrl = 'http://127.0.0.1:8080/simple/CDN/Website/';
var api = 'http://127.0.0.1:8080/simple/api/';
var site = 'http://127.0.0.1:8080/simple/Website';
var $arr = {};

angular
    .module('angApp', ['ui.router', 'app.controllers', 'app.services'])
    .config(AppConfig)
    .constant('appURL', api)
    .run(run);
    run.$inject = ['$rootScope', '$state', '$http', '$location', '$interval'];

// angular configuration function
function AppConfig($stateProvider, $urlRouterProvider, $locationProvider) 
{
    // pages / angular states
    $urlRouterProvider.otherwise('/404');
    $stateProvider
        .state({
            name: 'Home',
            url: '/',
            templateUrl: templateUrl+'Pages/Home.html',
            controller: 'mainCtrl',
        })
        .state({
            name: 'Blog',
            url: '/News',
            templateUrl: templateUrl+'Pages/Posts/Posts.html',
            controller: "postCtrl",
        })
        .state({
            name: 'categoryPost',
            url: '/News/:category',
            templateUrl: templateUrl+'Pages/Posts/Category.html',
            controller: "postCtrl",
        })
        .state({
            name: 'singlePost',
            url: '/News/:category/:posts',
            templateUrl: templateUrl+'Pages/Posts/Single.html',
            controller: "postCtrl",
        })
        .state({
            name: 'Contact-Us',
            url: '/Contact-Us',
        })
        .state({
            name: '404',
            url: '/404',
            templateUrl: templateUrl+'Pages/Error.html',
            controller: 'errCtrl',
        });
    $locationProvider.html5Mode({
                enabled: true,
                requireBase: false
        });
};

function run($rootScope, $state, $http, $location, $interval) 
{
    var lastDigestRun = Date.now();
    var idleCheck = $interval(function() {
        var now = Date.now();            
        if (now - lastDigestRun > 30*60*1000) {
            sessionStorage.clear();
            $location.path("/Accounts/Login");
        }
    }, 60*1000);
    getToken();

    $rootScope.$on('$locationChangeSuccess', function () 
    {
        lastDigestRun = Date.now();
        Seo(($location.path().split('/')[1] === '') ? "Home": $location.path().split('/')[1]);
        saveVisitorView(site+$location.path());
        $rootScope.globals = JSON.parse(sessionStorage.getItem('globals'));
    });

}

// this transforms any form data into proper encoding for ajax communication
function transformRequest(obj) 
{
    var $res = [];
    for (var key in obj) {
        $res.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return $res.join('&');
}

function getSeoData()
{
    if (!sessionStorage.getItem('seo')) {
        $.ajax({
            url: api+"Config/Pages/Website",
            type: "GET",
            dataType: "json",
            success: function (response, textStatus, jqXHR) {
                $.each(response.Output, function(key, arr){
                    $arr[arr.name] = arr;
                });
                sessionStorage.setItem(
                    'seo', JSON.stringify($arr)
                );
            },
            error: function (response, textStatus, jqXHR) {
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Content-Type', "application/x-www-form-urlencoded");
                xhr.setRequestHeader('X-Token', sessionStorage.getItem('xsrf'));
                xhr.setRequestHeader('Authorization', "Bearer "+ JSON.parse(sessionStorage.getItem('globals'))['currentUser']['authdata']);
            },
        });
    }
}

function saveVisitorView(url)
{
    $.ajax({
        url: api+"Analytics/Visitor/View",
        type: "POST",
        dataType: "json",
        data : {
            page : url
        },
        success: function (response, textStatus, jqXHR) {
            
        },
        error: function (response, textStatus, jqXHR) {
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Content-Type', "application/x-www-form-urlencoded");
            xhr.setRequestHeader('X-Token', sessionStorage.getItem('xsrf'));
            xhr.setRequestHeader('Authorization', "Bearer "+ JSON.parse(sessionStorage.getItem('globals'))['currentUser']['authdata']);
        },
    });
}

function saveVisitor()
{
        $.ajax({
            url: api+"Analytics/Visitor",
            type: "POST",
            dataType: "json",
            success: function (response, textStatus, jqXHR) {

            },
            error: function (response, textStatus, jqXHR) {
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Content-Type', "application/x-www-form-urlencoded");
                xhr.setRequestHeader('X-Token', sessionStorage.getItem('xsrf'));
                xhr.setRequestHeader('Authorization', "Bearer "+ JSON.parse(sessionStorage.getItem('globals'))['currentUser']['authdata']);
            },
        });
}

function generateId()
{
    return "6e6f7420636f6d706c657465";
}

function getToken() {
    if (!sessionStorage.getItem('globals')) {
        sessionStorage.setItem('xsrf', generateId());
        $.ajax({
            url: api+"Accounts/Guest",
            type: "GET",
            dataType: "json",
            success: function (response, textStatus, jqXHR) {
                sessionStorage.setItem(
                    'globals', 
                    JSON.stringify({
                        currentUser: {
                            username: response.Output.data.username,
                            authdata: response.Output.token
                        },
                        access: response.Output.data.access
                    })
                );
                getSeoData();
                saveVisitor();
            },
            error: function (response, textStatus, jqXHR) {
                getToken();
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Content-Type', "application/x-www-form-urlencoded");
                xhr.setRequestHeader('X-Token', sessionStorage.getItem('xsrf'));
            },
        });
    }
}

function Seo(title, description)
{
    var $seo = JSON.parse(sessionStorage.getItem('seo'));
    $('title').html(title.split('/')[0]+' - '+$seo[title].description);
}
