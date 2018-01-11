"use strict";

angular
    .module('app.services', [])
    .factory('AuthenticationService', AuthenticationService)
    .factory('BasicService', BasicService)
    .directive("ngFileModel", FileUploadDirective);
    FileUploadDirective.$inject = ['$parse', '$state', 'appURL', '$http', '$rootScope', 'BasicService'];
    AuthenticationService.$inject = ['$http', '$rootScope', 'appURL', 'localStorageService'];
    BasicService.$inject = ['$http', '$rootScope', 'appURL', 'localStorageService'];
         
    // AuthenticationService
    function AuthenticationService($http, $rootScope, appURL, localStorageService) 
    {
        var service = {};
 
        service.Login = Login;
        service.SetCredentials = SetCredentials;
        service.ClearCredentials = ClearCredentials;
        localStorageService.set('xsrf', generateId());
 
        return service;
 
        function Login(username, password, callback) 
        { 
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http.defaults.headers.post["X-Token"] = localStorageService.get('xsrf');
            $http
                .post(appURL + "Accounts/Login", transformRequest({ 
                    username: username, 
                    password: password 
                }))
                .success(function (data, status, headers, config) {
                    callback(data);
                }).error(function (data, status, headers, config) {
                    callback(data);
                }); 
        }
 
        function SetCredentials(username, password, token, data) 
        {
            $rootScope.globals = {
                currentUser: {
                    username: username,
                    authdata: token
                },
                access: data.access
            }; 
            localStorageService.set('globals', $rootScope.globals);
        }
 
        function ClearCredentials() {
            localStorageService.remove('globals');
            localStorageService.remove('xsrf');
        //     $rootScope.globals.currentUser = null;
        //     $rootScope.globals.access = null;
        }
    }

    function BasicService($http, $rootScope, appURL, localStorageService) 
    {
        var $url = appURL;
        var service = {};
        if($rootScope.globals === null)
        {
            window.location.href = "Login";
        }
        else{
            $http.defaults.headers.common["X-Token"] = localStorageService.get('xsrf');
        }
        service.GetData = GetData;
        service.serverRequest = serverRequest;
        //service.FilesPostData = FilesPostData;
 
        return service;
 
        function GetData(route, action, callback) 
        {
            $http.defaults.headers.common['Authorization'] = "Bearer "+ $rootScope.globals.currentUser.authdata;
            $http
                .get($url  + route + action)
                .success(function (data, status, headers, config) {
                    callback(data);
                }).error(function (data, status, headers, config) {
                    callback(data);
                });
        }

        function serverRequest(route, formData, method, callback)
        {
            $http.defaults.headers.common['Authorization'] = "Bearer "+ $rootScope.globals.currentUser.authdata;
            var $request;
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            switch(method)
            {
                case "PUT":
                {
                    $request = $http
                        .put($url + route, transformRequest(formData), {
                            transformRequest: angular.identity,
                        });
                    break;
                }
                case "DELETE":
                {
                    $request = $http
                        .delete($url + route, transformRequest(formData), {
                            transformRequest: angular.identity,
                        });
                    break;
                }
                case "POST":
                {
                    $request = $http
                        .post($url + route, transformRequest(formData), {
                            transformRequest: angular.identity,
                        });
                    break;
                }
            }
            $request
                .success(function (data, status, headers, config) {
                    callback(data);
                }).error(function (data, status, headers, config) {
                    callback(data);
                });
        }

    }

    function FilesPostData(route, Files, callback, $http, $rootScope)
    {
        $http.defaults.headers.common['Authorization'] = "Bearer "+ $rootScope.globals.currentUser.authdata;
        var datas = new FormData();
        if(Files != null)
        {
            angular.forEach(Files, function(file){  
                datas.append('file[]', file);  
           });  
        }
        $http.post(route + "/Files", datas,
        {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false}

        }).success(function(response){
            callback(response)
        }).error(function (data) {
            responseNotify("Error", "Posts Files", response);
        });
    }

    function FileUploadDirective($parse, $state, appURL, $http, $rootScope, BasicService) 
    {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var model = $parse(attrs.ngFileModel);
                var isMultiple = attrs.multiple;
                var modelSetter = model.assign;
                element.bind('change', function () {
                    var values = [];
                    angular.forEach(element[0].files, function (item) {
                        var value = {
                           // File Name 
                            name: item.name,
                            //File Size 
                            size: item.size,
                            //File URL to view 
                            url: URL.createObjectURL(item),
                            // File Input Value 
                            _file: item
                        };
                        values.push(value);
                    });
                    scope.$apply(function () {
                        if (isMultiple) {
                            modelSetter(scope, values);
                        } else {
                            modelSetter(scope, values[0]);
                        }
                    });
                    FilesPostData(
                        appURL + $state.current.url.split("/")[1], 
                        element[0].files, function(response){
                            if(response.Status === 200)
                            {
                                $('a#listBtn').click();
                                responseNotify("Success", "Posts Files", response.Output);
                                BasicService.GetData($state.current.url.split("/")[1]+"/Files", "", function(response)
                                {
                                    if (response.Status == 200) {
                                        $rootScope.Files = response.Output;
                                    } else {
                                        $scope.Error = response.Output;
                                    }
                                });
                            }
                            else{
                                responseNotify('Error', 'File Upload', response.Output)
                            }
                    }, $http, $rootScope)
                });
            }
        };
    };