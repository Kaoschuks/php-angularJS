"use strict";

angular
    .module('app.controllers', ['app.services'])
    .controller("mainCtrl", mainController)
    .controller("authCtrl", authController)
    .controller("errCtrl", errorController)
    .controller("postCtrl", postController);

function mainController($scope, $state, BasicService, $location, serverCall) 
{
    var init = serverCall.setSeo($state.current.name);

    // (function initController() {
    //     // get all blog data
        
    // });
};

function postController($scope, $state, BasicService, $rootScope, $location, serverCall) 
{
    var posts = this; 
    posts.getPostCategories = getPostCategories;
    posts.getAllPost = getAllPost;
    posts.singlePost = singlePost;
    posts.posted_on = new Date().toUTCString();
    (function initController() {
        // get all blog data
        serverCall.setSeo($state.current.name);
        switch($state.current.name)
        {
            case "Blog":
            {
                getAllPost();
                break;
            }
            case "categoryPost":
            {
                getPostCategories();
                break;
            }
            case "singlePost":
            {
                singlePost($state.params.posts);
                break;
            }
        }
    })();

    function getAllPost()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("Posts", "", function(response)
        {
            if (response.Status == 200) {
                $scope.Posts = response.Output;
                $rootScope.dataLoading = false;
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
    };

    function getPostCategories()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("Posts", "/Category", function(response)
        {
            if (response.Status == 200) {
                $scope.Error = null;
                $scope.categories = response.Output;
                $rootScope.dataLoading = false;
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
    };

    function singlePost(title)
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("Posts/title/", title, function(response)
        {
            if (response.Status == 200) {
                $scope.data = response.Output;
                posts.title = response.Output[0].title;
                posts.description = response.Output[0].description;
                posts.author = response.Output[0].author;
                posts.category = response.Output[0].category;
                $rootScope.dataLoading = false;
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
    }
};

function authController($scope, $state, $rootScope, $location, AuthenticationService, serverCall) 
{
    var vm = this; 
    vm.login = login; 
    (function initController() {
        // reset login status
        checkAccess();
        serverCall.setSeo($state.current.name);
        switch($state.current.name)
        {
            case "Logout":
            {
                AuthenticationService.ClearCredentials();
                $location.path('/Accounts/Login');
                break;
            }
        }
    })();

    function checkAccess()
    {
        var user = JSON.parse(sessionStorage.getItem('globals'));
        if(!user || user.access === "Guest")
        {
            $location.path("/Accounts/Login");
        }
        else{
            $location.path("/Accounts/Dashboard");
        }
    }
 
    function login() 
    {
        AuthenticationService.ClearCredentials();
        vm.dataLoading = true;
        AuthenticationService.Login("Auth", vm.username, vm.password, "/Login", function (response) 
        {
            if (response.Status == 200) {
                AuthenticationService.SetCredentials(vm.username, vm.password, response.Output['token'], response.Output['data']);
                $location.path('/Accounts/Dashboard');
                vm.dataLoading = false;
            } else {
                vm.dataLoading = false;
                vm.Error = response.Output;
                vm.username = null;
                vm.password = null;
            }
        });
    };
}

function errorController($scope, $state, $location, serverCall)
{
    (function initController() {
        serverCall.setSeo($state.current.name);
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
