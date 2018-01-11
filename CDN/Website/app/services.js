"use strict";

angular
    .module('app.services', [])
    .factory("serverCall", serverCall)
    .factory('AuthenticationService', AuthenticationService)
    .factory('BasicService', BasicService)
    .factory("protectData", protectData)
    .directive('pgSidebar',function(){return{restrict:'A',link:function(scope,element,attrs){var $sidebar=$(element);$sidebar.sidebar($sidebar.data());$('body').on('click','.sidebar-menu a',function(e){if($(this).parent().children('.sub-menu')===false){return;}var el=$(this);var parent=$(this).parent().parent();var li=$(this).parent();var sub=$(this).parent().children('.sub-menu');if(li.hasClass("active open")){el.children('.arrow').removeClass("active open");sub.slideUp(200,function(){li.removeClass("active open");});}else{parent.children('li.open').children('.sub-menu').slideUp(200);parent.children('li.open').children('a').children('.arrow').removeClass('active open');parent.children('li.open').removeClass("open active");el.children('.arrow').addClass("active open");sub.slideDown(200,function(){li.addClass("active open");});}});}}})
    .directive("fileUploadDirective", FileUploadDirective);
    AuthenticationService.$inject = ['$http', '$rootScope', 'appURL', 'BasicService'];
    BasicService.$inject = ['$http', '$rootScope', 'appURL'];
    serverCall.$inject = ['appURL', 'BasicService', '$rootScope'];
    FileUploadDirective.$inject = ['$parse'];

    function FileUploadDirective($parse) 
    {
        return {
            restrict: 'A', //the directive can be used as an attribute only
            link: function (scope, element, attrs) 
            {
                var model = $parse(attrs.demoFileModel),
                    modelSetter = model.assign; //define a setter for demoFileModel
 
                //Bind change event on the element
                element.bind('change', function () {
                    //Call apply on scope, it checks for value changes and reflect them on UI
                    scope.$apply(function () {
                        //set the model value
                        modelSetter(scope, element[0].files[0]);
                    });
                });
            }
        };
     };
     
    // AuthenticationService
    function AuthenticationService($http, $rootScope, appURL, BasicService) 
    {
        var service = {};

        service.Login = Login;
        service.SetCredentials = SetCredentials;
        service.ClearCredentials = ClearCredentials;
        sessionStorage.setItem('xsrf', generateId());
 
        return service;
 
        function Login(route, username, password, action, callback) 
        { 
            BasicService.PostData(
                route + action, 
                {
                    uname: username,
                    passwd: password
                }, 
                function(response){
                    callback(response)
                }
            );
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
            sessionStorage.setItem('globals', JSON.stringify($rootScope.globals));
        }
 
        function ClearCredentials() {
            sessionStorage.removeItem('globals');
            sessionStorage.removeItem('xsrf');
            sessionStorage.setItem('xsrf', generateId());
            //localStorageService.clear();
        }
    }

    function BasicService($http, $rootScope, appURL) 
    {
        var $url = appURL;
        var service = {};
 
        service.GetData = GetData;
        service.PostData = PostData;
        service.FilePostData = FilePostData;
        if(sessionStorage.getItem('globals') != null)
        {
            $http.defaults.headers.common['Authorization'] = "Bearer "+ JSON.parse(sessionStorage.getItem('globals'))['currentUser']['authdata'];
        }
        $http.defaults.headers.common["X-Token"] = sessionStorage.getItem('xsrf');
 
        return service;
 
        function GetData(route, action, callback) 
        {
            $http
                .get($url  + route + action)
                .success(function (data, status, headers, config) {
                    callback(data);
                }).error(function (data, status, headers, config) {
                    callback(data);
                });
        }
 
        function PostData(route, formData, callback) 
        {
            $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
            $http
                .post($url + route, transformRequest(formData), {
                    transformRequest: angular.identity,
                })
                .success(function (data, status, headers, config) {
                    callback(data);
                }).error(function (data, status, headers, config) {
                    callback(data);
                });
        }

        function FilePostData(route, formData, file, callback)
        {
            var datas = new FormData();
            $.each(formData, function(index, data){
                datas.append(index, data);
            });
            if(file != null){
                for (var i = 0; i < file.length; i++) {
                    datas.append('file' + i, file[i]);
                }
            }
            $.ajax({
                url : $url + route,
                type : "POST",
                data : datas,
                global : false,
                dataType : "json",
                processData: false,  
                contentType: false,  
                success : function(response, textStatus, jqXHR) {
                    callback($.extend({}, response.output));
                },
                error : function(response, textStatus, jqXHR) {
                    callback($.extend({}, response.output));
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer "+$rootScope.globals.currentUser.authdata);
                    xhr.setRequestHeader('X-Token', sessionStorage.getItem('xsrf'));
                },
            });
        }
    }

    // Base64 encoding service used by AuthenticationService
    var Base64 = {
 
        keyStr: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=',
 
        encode: function (input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
 
            do {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);
 
                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;
 
                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }
 
                output = output +
                    this.keyStr.charAt(enc1) +
                    this.keyStr.charAt(enc2) +
                    this.keyStr.charAt(enc3) +
                    this.keyStr.charAt(enc4);
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
            } while (i < input.length);
 
            return output;
        },
 
        decode: function (input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
 
            // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
            var base64test = /[^A-Za-z0-9\+\/\=]/g;
            if (base64test.exec(input)) {
                window.alert("There were invalid base64 characters in the input text.\n" +
                    "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
                    "Expect errors in decoding.");
            }
            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
 
            do {
                enc1 = this.keyStr.indexOf(input.charAt(i++));
                enc2 = this.keyStr.indexOf(input.charAt(i++));
                enc3 = this.keyStr.indexOf(input.charAt(i++));
                enc4 = this.keyStr.indexOf(input.charAt(i++));
 
                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;
 
                output = output + String.fromCharCode(chr1);
 
                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }
 
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
 
            } while (i < input.length);
 
            return output;
        }
    };

    // decimal to hexadecinal
    function dec2hex (dec) 
    {
        return ('0' + dec.toString(16)).substr(-2)
    }

    function serverCall(appURL, BasicService, $rootScope)
    {
        return {
            subcribe: function(data, callback) 
            {
                BasicService.PostData
                (
                    "/Email", 
                    { 
                        action: "subcribe", 
                        message: mail.message, 
                    }, 
                    function(response)
                    {
                        if (response.Status == 200) {
                            $scope.Error = null;
                            $scope.newsLetterResponse = response.Output;
                        } else {
                            $scope.Error = response.Output;
                        }
                    }
                );
            },
            websockets: function(route, action, data, token, xsrf, callback) 
            {
                callback(response);
            },
            setSeo: function(title)
            {
                $rootScope.title = title;
            },
            shareLink: function()
            {
            }
        };
    }

    function protectData()
    {
        return {
            encryptData: function(rawData) 
            {
                return chats;
            },
            decryptData: function(encryptedData) 
            {
                return chats;
            }
        };
    }